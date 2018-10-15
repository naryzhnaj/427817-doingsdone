<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="" method="post" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>
        
        <?php $error_class = isset($errors['name']) ? "form__input--error" : ""; ?>
        <input class="form__input <?=$error_class;?>" type="text" name="name" id="name"
            placeholder="Введите название"  required
            value=<?=(isset($_POST['name'])) ? htmlspecialchars($_POST['name']) : '';?>>
        
        <?php if (isset($errors['name'])):?>
            <p class="form__message"><span class ="form__message error-message"><?=$errors['name']; ?></span></p>
        <?php endif;?>
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>
        <select class="form__input form__input--select" name="project" id="project">
            <?php foreach ($projects as $project): ?>
                <option value="<?=$project['id'] ?>"><?=$project['title']; ?></option>                 
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form__row">   
        <label class="form__label" for="date">Дата выполнения</label>
        
        <?php $error_class = isset($errors['date']) ? "form__input--error" : ""; ?>
        <input class="form__input form__input--date <?=$error_class;?>" type="date" name="date" id="date" 
            placeholder="Введите дату в формате ДД.ММ.ГГГГ"
            value=<?=(isset($_POST['date'])) ? htmlspecialchars($_POST['date']) : '';?>>
        
        <?php if (isset($errors['date'])):?>
            <p class="form__message"><span class ="form__message error-message"><?=$errors['date']; ?></span></p>
        <?php endif;?>
    </div>

    <div class="form__row">
        <label class="form__label" for="preview">Файл</label>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="preview" id="preview" value="">

            <label class="button button--transparent" for="preview">
                <span>Выберите файл</span>
            </label>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>