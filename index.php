<?php
require 'connect.php';

function addUser($name, $email, $groupIds = []) {
    $db = dbConnect();
    $db->beginTransaction();
    try {
      
        $checkStmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->fetch()) {
            throw new PDOException("Email уже существует.");
        }
        $insertUserStmt = $db->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
        $insertUserStmt->execute([':name' => $name, ':email' => $email]);
        $userId = $db->lastInsertId();
        if (!empty($groupIds)) {
            $insertGroupStmt = $db->prepare("INSERT INTO user_groups (user_id, group_id) VALUES (:user_id, :group_id)");
            foreach ($groupIds as $groupId) {
                $insertGroupStmt->execute([':user_id' => $userId, ':group_id' => $groupId]);
            }
        }
        $db->commit();
        return $userId;
    } catch (PDOException $e) {
        $db->rollBack();
        throw $e;
    }
}

function getUsers() {
    $db = dbConnect();
    $stmt = $db->query('SELECT * FROM users ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

function updateUser($id, $name, $email, $groupIds = []) {
    $db = dbConnect();
    $db->beginTransaction();
    try {
        $checkStmt = $db->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $checkStmt->execute([':email' => $email, ':id' => $id]);
        if ($checkStmt->fetch()) {
            throw new PDOException("Email уже существует.");
        }
        $stmtUpd = $db->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
        $stmtUpd->execute([':id' => $id, ':name' => $name, ':email' => $email]);
        $stmtDel = $db->prepare('DELETE FROM user_groups WHERE user_id = :id');
        $stmtDel->execute([':id' => $id]);
        if (!empty($groupIds)) {
            $stmtIns = $db->prepare("INSERT INTO user_groups (user_id, group_id) VALUES (:user_id, :group_id)");
            foreach ($groupIds as $groupId) {
                $stmtIns->execute([':user_id' => $id, ':group_id' => $groupId]);
            }
        }
        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        throw $e;
    }
}

function deleteUser($id) {
    $db = dbConnect();
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

function getGroups() {
    $db = dbConnect();
    $stmt = $db->query('SELECT * FROM groups ORDER BY name DESC');
    return $stmt->fetchAll();
}

function addGroup($name) {
    $db = dbConnect();
    $stmt = $db->prepare("INSERT INTO groups (name) VALUES (:name)");
    $stmt->execute([':name' => $name]);
    return $db->lastInsertId();
}
?>