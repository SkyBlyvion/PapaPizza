
<main class="container-form">
    <h1 class="title">Modifier le profil</h1>
    <!-- on va afficher les erreurs s'il y en a -->
    <?php if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>


    <form class="auth-form" action="/updated-user" method="POST">
        <input type="hidden" name="id" value="<?php echo $user_id ?>">
        <div class="box-auth-input">
            <label class="detail-description">Adresse email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="box-auth-input">
            <label class="detail-description">Nom</label>
            <input type="text" class="form-control" name="lastname">
        </div>
        <div class="box-auth-input">
            <label class="detail-description">Prénom</label>
            <input type="text" class="form-control" name="firstname">
        </div>
        <div class="box-auth-input">
            <label class="detail-description">Téléphone</label>
            <input type="number" class="form-control" name="phone">
        </div>
        <button type="submit" class="call-action">Mettre à jour</button>
    </form>

</main>