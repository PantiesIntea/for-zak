
<?php
    $data = $_POST;
    require "db.php";

    if(isset($data['do_addnewproducts'])) {
        $errors = array();
        if(trim($data['products_title']) == '' ) {
            $errors[] = 'Введите имя продукта!';
        }
        if($data['description'] == '' ) {
            $errors[] = 'Введите description';
        }
        if($data['product_class'] == '' ) {
            $errors[] = 'Введите product class';
        }
        if($data['product_brand'] == '' ) {
            $errors[] = 'Введите product brand';
        }
        if($data['price'] == '' ) {
            $errors[] = 'Введите price';
        }

        if (empty($errors)) {
        $products = R::dispense('products');
        $products->product_title = $data['products_title'];
        $products->description = $data['description'];
        $products->price = $data['price'];

        $brand = $data['product_brand'];

        $productbrand  = R::findOne( 'productbrand', ' productbrand_name = ? ', ["$brand"]);
        $brand_id = $productbrand->id;
        $products->productbrand = $productbrand->id;

        $cat = $data['cat_id'];

        $category  = R::findOne( 'categories', ' category_name = ? ', ["$cat"]);
        $cat_id = $category->id;
        $products->category = $category->id;

        
        $class = $data['product_class'];

        $productclass  = R::findOne( 'productclass', ' productclass_name = ? ', ["$class"]);
        $class_id = $productclass->id;
        $products->productclass = $productclass->id;

        $myimg1 = 'uploads/'. time(). $_FILES['picture1']['name'];
        if (move_uploaded_file($_FILES['picture1']['tmp_name'], $myimg1)) {
            $data['picture1'] = $myimg1;
        }
        $products->picture1 = $data['picture1'];

        $myimg2 = 'uploads/'. time(). $_FILES['picture2']['name'];
        if (move_uploaded_file($_FILES['picture2']['tmp_name'], $myimg2)) {
            $data['picture2'] = $myimg2;
        }
        $products->picture2 = $data['picture2'];

        $myimg3 = 'uploads/'. time(). $_FILES['picture3']['name'];
        if (move_uploaded_file($_FILES['picture3']['tmp_name'], $myimg3)) {
            $data['picture3'] = $myimg3;
        }
        $products->picture3 = $data['picture3'];

        $myimg4 = 'uploads/'. time(). $_FILES['picture4']['name'];
        if (move_uploaded_file($_FILES['picture4']['tmp_name'], $myimg4)) {
            $data['picture4'] = $myimg4;
        }
        $products->picture4 = $data['picture4'];

        R::store($products);

        $countchar = R::count('characteristics', 'category = ?', ["$cat_id"]);

        for ($i = 1; $i <= $countchar; $i++) {
            $charcreate = R::dispense('charprod');
            $charcreate->charvalue = NULL;
            $charcreate->product = $products->id;
            R::store($charcreate);
        }

        } else {
            echo '<div style="color: 
                       red; position: absolute; 
                       z-index: 5000;
                       margin-left: 50%;
                       margin-top: 55px;">'.array_shift($errors).'</div>';
        }
        header('location: products.php');
    }
?>

<?php if (isset($_SESSION['logged_user'])) : ?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Admin Pannel</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCatalog" aria-expanded="false" aria-controls="collapseCatalog">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Catalog
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseCatalog" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="categories.php">Categories</a>
                                    <a class="nav-link" href="productcalss.php">Product calss</a>
                                    <a class="nav-link" href="productsbrand.php">Product brand</a>
                                    <a class="nav-link" href="characteristics.php">Characteristics</a>
                                    <a class="nav-link" href="characteristicsvalue.php">Characteristic value</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="products.php">
                                <div class="sb-nav-link-icon"><i i class="fas fa-book-open"></i></div>
                                Products
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo $_SESSION['logged_user']->email ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-6">
                        <h1 class="mt-4">Add new</h1>
                        <ol class="breadcrumb mb-4">
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                            <form action="addnewproducts.php" enctype="multipart/form-data" method="POST">
                            <div class="tabs">
                                <input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
                                <label for="tab-btn-1">Information</label>
                                <input type="radio" name="tab-btn" id="tab-btn-2" value="">
                                <label for="tab-btn-2">Description</label>
                                <input type="radio" name="tab-btn" id="tab-btn-3" value="">
                                <label for="tab-btn-3">Images</label>
                                <div id="content-1">
                                <div class="card-body">
                                <div>
                                        <label>Product сlass</label>
                                        <select name="product_class" class="dataTable-input" type="text">
                                        <?php
                                        $productclass = R::findAll('productclass');
                                                foreach ($productclass as $productsclass) {
                                                echo '<option>'. $productsclass->productclass_name .'</option>';
                                                }
                                        ?>
                                        <option selected hidden></option>
                                        </select>
                                        <label>Brand</label>
                                        <select name="product_brand" class="dataTable-input" type="text">
                                        <?php
                                        $productbrand = R::findAll('productbrand');
                                                foreach ($productbrand as $productsbrand) {
                                                echo '<option>'. $productsbrand->productbrand_name .'</option>';
                                                }
                                                echo '<option>None</option>'
                                        ?>
                                        <option selected hidden></option>
                                        </select>
                                        <label>Title</label>
                                        <input name="products_title" class="dataTable-input" type="text">
                                    </div>
                                    <div>
                                        <label>Category</label>
                                        <select name="cat_id" class="dataTable-input" type="text">
                                        <?php
                                        $categories = R::findAll('categories');
                                                foreach ($categories as $category) {
                                                echo '<option>'. $category->category_name .'</option>';
                                                }
                                        ?>
                                        <option selected hidden></option>
                                        </select>
                                    </div>
                                    <button name="do_addnewproducts" style="margin-top: 10px;" class="btn btn-primary">Create</button>
                                    <button name="do_delete" style="margin-top: 10px; background-color: red;" class="btn btn-primary">Delete</button>
                                </div>
                                </div>
                                <div id="content-2">
                                    <div>
                                            <label>Description</label>
                                            <input name="description" class="dataTable-input" type="text" >
                                    </div>
                                    <div>
                                            <label>Price</label>
                                            <input name="price" class="dataTable-input" type="text">
                                            <button name="do_addnewproducts" style="margin-top: 10px;" class="btn btn-primary">Create</button>
                                    </div>
                                </div>
                                <div id="content-3">
                                <label>Picture 1</label>
                                            <input name="picture1" class="dataTable-input" type="file" value="asd">
                                            <label>Picture 2</label>
                                            <input name="picture2" class="dataTable-input" type="file" value="asd">
                                            <label>Picture 3</label>
                                            <input name="picture3" class="dataTable-input" type="file" value="asd">
                                            <label>Picture 4</label>
                                            <input name="picture4" class="dataTable-input" type="file" value="asd">
                                <button  name="do_addnewproducts" style="margin-top: 10px;" class="btn btn-primary" onclick="window.location.href='products.php';">Create</button><br>
                                </div>
                                </form>
                        </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
            function addEl() {
            let inputs = document.querySelectorAll('select[type="text"]')
            let lastNum = ((inputs[inputs.length-1]).getAttribute('name'));
            let nextNum = Number(lastNum) + 1;

            let elem = document.createElement("p");
            elem.innerHTML = `Характеристика ${nextNum}: <select type="text" id="in${nextNum}" name="${nextNum}"> 
            <?php
                                            $chars = R::findAll('characteristicvalue');
                                            foreach($chars as $char) {
                                                echo '<option class="count">'.$char->characteristicvalue.'</option>';
                                            } 
            ?>
            </select>`;

            let parentGuest = document.getElementById("in"+lastNum);
            parentGuest.parentNode.insertBefore(elem, parentGuest.nextSibling);
            }
        </script>
    </body>
</html>

<?php else : ?>
<?php
    header('location: login.php');
?>
<?php endif ?>