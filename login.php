<?php
  require "db.php";
  $data = $_POST;

  if(isset($data['do_login'])) {
    $errors = array();
    $user = R::findOne('users', 'email = ?', array($data['email']));
    if($user ) {
        if (password_verify($data['password'], $user->password)) {
            $_SESSION['logged_user'] = $user;
            header('location: /'); 
            if ($user->status == 0) {
            } else {
                unset($_SESSION['logged_user']);
            }
        }else {
            $errors[] = 'Неверный пароль!';
        }
    } else {
        $errors[] = 'Неверный логин или пароль!';
    }
    if (! empty($errors)) {
        echo '<div style="color: red;">'.array_shift($errors).'</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
  </head>
  <body class="bg-primary">
    <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
        <main>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                  <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Login</h3>
                  </div>
                  <div class="card-body">
                    <form action="login.php" method="POST">
                      <div class="form-floating mb-3">
                        <input
                          class="form-control"
                          id="inputEmail"
                          type="email"
                          name="email"
                          placeholder="name@example.com"
                        />
                        <label for="inputEmail">Email address</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input
                          class="form-control"
                          id="inputPassword"
                          type="password"
                          name="password"
                          placeholder="Password"
                        />
                        <label for="inputPassword">Password</label>
                      </div>
                      <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" name="do_login" class="btn btn-primary" href="index.html">Login</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
      <div id="layoutAuthentication_footer"></div>
    </div>
    <script src="js/scripts.js"></script>
  </body>
</html>
