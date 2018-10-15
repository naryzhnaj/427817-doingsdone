<h2 class="content__main-heading">Регистрация аккаунта</h2>
<form class="form" action="" method="post">
  <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>
    <input class="form__input" type="text" name="email" id="email" value="" placeholder="Введите e-mail" required>
      <?php if (isset($errors['email'])) : ?>
          <p class="form__message">E-mail введён некорректно</p>
      <?php endif; ?> 
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>
    <input class="form__input" type="password" name="password" id="password" value="" placeholder="Введите пароль" required>
    <?php if (isset($errors['password'])) : ?>
          <p class="form__message">Введите пароль</p>
      <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="name">Имя <sup>*</sup></label>
    <input class="form__input" type="text" name="name" id="name" value="" placeholder="Введите имя" required>
    <?php if (isset($errors['name'])) : ?>
          <p class="form__message">Введите имя</p>
    <?php endif; ?>
  </div>

  <div class="form__row form__row--controls">
    <?php if ($errors) :?>
          <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>
    <input class="button" type="submit" name="" value="Зарегистрироваться">
  </div>
</form>