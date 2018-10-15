<h2 class="content__main-heading">Добавление проекта</h2>
<form class="form"  action="" method="post">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>
        <input class="form__input" type="text" name="name" id="project_name" 
            placeholder="Введите название проекта" required
            value=<?=(isset($_POST['name'])) ? htmlspecialchars($_POST['name']) : ''; ?>>

        <?php if (isset($errors['name'])):?>
            <p class="form__message"><span class ="form__message error-message"><?=$errors['name']; ?></span></p>
        <?php endif;?>
    </div>
    
    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>