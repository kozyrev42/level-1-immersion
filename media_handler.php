<?php
require_once('functions.php');
session_start();

// id редактируемого пользевателя
$id_user = $_POST['id'];

// данные по файлу
$image_name = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];


// загрузка аватар
function upload_avatar($image_name, $id_user)
{
    // если аватар не выбран, выход из функции
    if (empty($image_name)) {
        return false;
    }

    // если аватар выбран
    if (!empty($image_name)) {

        // есть ли аватар в базе
        $name_avatar = has_avatar($id_user);

        // аватар в базе ЕСТЬ
        if (!empty($name_avatar)) {
            // удаление файла из каталога 
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
            $query = "SELECT * FROM `users-dive` WHERE id=:id";
            $statement = $pdo->prepare($query);
            $statement->execute(['id' => $id_user]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            // прописать путь до удаляемого файла
            @unlink("img/demo/avatars/" . $result['avatar']);

            // загрузка новой
            // получим расширение файла
            $extension = pathinfo($image_name)["extension"];
            // формируем уникальное имя файла
            $uniq_image_name = uniqid() . "." . $extension;

            // сохранить картинку в постоянную папку
            // формируем путь сохранения, откуда
            $tmp_name = $_FILES['image']['tmp_name'];
            //куда
            $target = "img/demo/avatars/" . $uniq_image_name;
            // перемещаем в постоянную папку
            move_uploaded_file($tmp_name, $target);

            // записать в базу имени загруженего файла
            //$pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
            $query = "UPDATE `users-dive` SET avatar=:avatar WHERE id=:id";
            $statement = $pdo->prepare($query);
            $statement->execute(['avatar' => $uniq_image_name, 'id' => $id_user]);
            return true;
        }


        // аватара в базе НЕТ, загружаем картинку, обновляем базу
        if (!$name_avatar) {
            // получим расширение файла
            $extension = pathinfo($image_name)["extension"];
            // формируем уникальное имя файла
            $uniq_image_name = uniqid() . "." . $extension;

            // сохранить картинку в постоянную папку
            // формируем путь сохранения, откуда
            $tmp_name = $_FILES['image']['tmp_name'];
            //куда
            $target = "img/demo/avatars/" . $uniq_image_name;
            // перемещаем в постоянную папку
            move_uploaded_file($tmp_name, $target);

            // записать в базу имени загруженего файла
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
            $query = "UPDATE `users-dive` SET avatar=:avatar WHERE id=:id";
            $statement = $pdo->prepare($query);
            $statement->execute(['avatar' => $uniq_image_name, 'id' => $id_user]);
            return true;
        }
    }
}

// вызов функции
$upload_avatar = upload_avatar($image_name, $id_user);

// возврат false
if (!$upload_avatar) {
    set_flash_massage("success", "Аватар НЕ выбран!");
    redirect_to("page_profile.php?id=$id_user");
}


// возврат true
if ($upload_avatar) {
    set_flash_massage("success", "Аватар изменён!");
    redirect_to("page_profile.php?id=$id_user");
}
