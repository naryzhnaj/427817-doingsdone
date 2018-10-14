<form class="form" action="" method="post">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>
        
        <?php $error_class = isset($errors['email']) ? "form__input--error" : ""; ?>
        <input class="form__input  <?=$error_class;?>" type="text" name="email" id="email" value="" placeholder="Введите e-mail" required>
        <?php if (isset($errors['email'])):?>
            <p class="form__message"><span class ="form__message error-message"><?=$errors['email']; ?></span></p>
        <?php endif;?>
    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <?php $error_class = isset($errors['password']) ? "form__input--error" : ""; ?>
        <input class="form__input <?=$error_class;?>" type="password" name="password" id="password" value="" placeholder="Введите пароль" required>
        <?php if (isset($errors['password'])):?>
            <p class="form__message"><span class ="form__message error-message"><?=$errors['password']; ?></span></p>
        <?php endif;?>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Войти">
    </div>
</form>