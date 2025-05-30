<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$profileFile = __DIR__ . '/profile.php';
$profile = file_exists($profileFile) ? include $profileFile : [];

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $birthdate = $_POST['birthdate'];
    $bio = trim($_POST['bio']);
    $photoPath = $profile['photo'] ?? '';

    $birthDateTime = DateTime::createFromFormat('Y-m-d', $birthdate);
    $birthDateTimeErrors = DateTime::getLastErrors();

    if (!$name || !$surname || !$birthDateTime || !$bio) {
        $_SESSION['error'] = 'Усі поля обов’язкові для заповнення.';
    } elseif (mb_strlen($name) < 2 || mb_strlen($surname) < 2) {
        $_SESSION['error'] = 'Ім’я та прізвище мають містити більше одного символу.';
    } elseif (!$birthDateTime || $birthDateTimeErrors['warning_count'] > 0 || $birthDateTimeErrors['error_count'] > 0) {
        $_SESSION['error'] = 'Неправильний формат дати.';
    } else {
        $age = date_diff($birthDateTime, new DateTime())->y;
        if ($age < 16 || $age > 150) {
            $_SESSION['error'] = 'Вік користувача має бути від 16 до 150 років.';
        } elseif (mb_strlen($bio) < 50) {
            $_SESSION['error'] = 'Опис має містити щонайменше 50 символів.';
        } else {
            if (!empty($_FILES['photo']['name'])) {
                if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $finfo = mime_content_type($_FILES['photo']['tmp_name']);
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (in_array($finfo, $allowedTypes)) {
                        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                        $newPhotoPath = 'assets/' . uniqid('profile_') . '.' . $ext;

                        if (!empty($photoPath) && file_exists($photoPath)) {
                            unlink($photoPath);
                        }

                        move_uploaded_file($_FILES['photo']['tmp_name'], $newPhotoPath);
                        $photoPath = $newPhotoPath;
                    } else {
                        $_SESSION['error'] = 'Фото має бути JPG, PNG або GIF.';
                    }
                } else {
                    $_SESSION['error'] = 'Помилка при завантаженні фото.';
                }
            }

            if (!isset($_SESSION['error'])) {
                $profile = [
                    'name' => $name,
                    'surname' => $surname,
                    'birthdate' => $birthdate,
                    'bio' => $bio,
                    'photo' => $photoPath,
                ];
                file_put_contents($profileFile, "<?php return " . var_export($profile, true) . ";");
                $_SESSION['success'] = 'Профіль успішно збережено.';
            }
        }
    }

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Мій профіль</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="product-page" style="max-width: 1000px; margin: auto; padding: 2rem;">
    <h1>Профіль користувача</h1>

    <?php if ($error): ?>
        <div class="error-message" style="color: red; margin-bottom: 1rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php elseif ($success): ?>
        <div class="cart-success" style="color: green; margin-bottom: 1rem;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="profile-form" style="display: flex; gap: 2rem;">
        <div style="flex: 1; text-align: center;">
            <?php
            $photoWebPath = $profile['photo'] ?? '';
            $photoFilePath = __DIR__ . '/' . $photoWebPath;
            $photoToShow = (isset($photoWebPath) && file_exists($photoFilePath)) ? $photoWebPath : 'assets/default-user.png';
            ?>
            <img src="<?= htmlspecialchars($photoToShow) ?>"
                 alt="Фото користувача"
                 style="max-width: 60%; border-radius: 8px; margin-bottom: 1rem;">
            <label for="photo-upload" class="custom-file-upload"
                   style="display: inline-block; padding: 0.5rem 1rem; background-color: #4CAF50; color: white; border-radius: 6px; cursor: pointer;">
                Обрати фото
            </label>
            <input id="photo-upload" type="file" name="photo" accept="image/*" style="display: none;">
        </div>

        <div style="flex: 2;">
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <input type="text" name="name" placeholder="Ім’я" value="<?= htmlspecialchars($profile['name'] ?? '') ?>"
                       style="flex: 1; padding: 0.5rem;">
                <input type="text" name="surname" placeholder="Прізвище"
                       value="<?= htmlspecialchars($profile['surname'] ?? '') ?>" style="flex: 1; padding: 0.5rem;">
                <input type="date" name="birthdate" value="<?= htmlspecialchars($profile['birthdate'] ?? '') ?>"
                       style="padding: 0.5rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <textarea name="bio" rows="10" placeholder="Стисла інформація про себе..."
                          style="width: 100%; resize: none; padding: 0.75rem;"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="submit-button"
                        style="background-color: #4CAF50; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px;">
                    Зберегти
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('photo-upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.querySelector('.product-page img');
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>
