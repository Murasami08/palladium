<?php
require 'index.php';
$groupId1 = addGroup('Admin');
$groupId2 = addGroup('Editor');

$userId1 = addUser('Ivanov Ivan', 'ivan@haha.ru', [$groupId1, $groupId2]);
$userId2 = addUser('Sergeev Sergey', 'sergey@ahah.ru', [$groupId2]);

echo "Список пользователей:\n";
$users = getUsers();
print_r($users);

updateUser($userId1, 'Petrov Petr', 'petr@hhaa.ru', [$groupId1]);
echo "Обновленный список пользователей:\n";

$users = getUsers();
print_r($users);
deleteUser($userId2);

echo "Список пользователей после удаления:\n";
$users = getUsers();
print_r($users);
?>