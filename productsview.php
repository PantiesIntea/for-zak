<?php

use RedBeanPHP\Util\Dump;


    $data = $_POST;
    error_reporting(0);



    require "db.php";

    // if($data){
    //     echo('<pre>');
    //     $data['characteristic'] = array_values(array_filter($data['characteristic']));
    //     print_r($data);
    //     echo('</pre>');
    //     die();
    // }
    $link = mysqli_connect("localhost", "root", "root", "someshop");

    $productget = (int)$_GET['key'];
    $productone = R::findOne('products', 'id = ?', [$productget]);

    if (isset($data['do_edit'])) {
        $productone = R::findOne('products', 'id = ?', [$productget]);
        $productone->product_title = $data['product_title'];
        $productone->description = $data['description'];
        $productone->price = $data['price'];

        $brand = $data['product_brand'];

        $productbrand  = R::findOne( 'productbrand', ' productbrand_name = ? ', ["$brand"]);
        $brand_id = $productbrand->id;
        $productone->productbrand = $productbrand->id;

        $cat = $data['cat_id'];

        $category  = R::findOne( 'categories', ' category_name = ? ', ["$cat"]);
        $cat_id = $category->id;
        $productone->category = $category->id;

        $class = $data['product_class'];

        $productclass  = R::findOne( 'productclass', ' productclass_name = ? ', ["$class"]);
        $class_id = $productclass->id;
        $productone->productclass = $productclass->id;

        $countchar = R::count('characteristics', 'category = ?', ["$productone->category"]);

        // for ($i = 1; $i <= $countchar; $i++) {
        //     $idd = $productone->id;
        //     $charedit = R::findOne('charprod', 'product = ?', ["$idd"]);
        //     $prodid = $productone->id;
        //     $charedit->charvalue = $data['characteristic'];
        //     $charedit->product = $prodid;
        //     R::store($charedit);
        // }

        $rowiter = $data['characteristic'];
        $sqlq="DELETE FROM charprod WHERE product = $productone->id";
        mysqli_query($link, $sqlq);

        foreach ($rowiter as $row) {
            $sql="INSERT INTO  charprod (charvalue, product) VALUES ('$row', '$productone->id')";      
            mysqli_query($link, $sql);
            var_dump($link, $sql);
        }

        $myimg1 = 'uploads/'. time(). $_FILES['picture1']['name'];
        if (move_uploaded_file($_FILES['picture1']['tmp_name'], $myimg1)) {
            $data['picture1'] = $myimg1;
        }
        if ($data['picture1'] == '') {
            $productone->picture1 = $productone->picture1;
        } else {
            $productone->picture1 = $data['picture1'];
        }

        $myimg2 = 'uploads/'. time(). $_FILES['picture2']['name'];
        if (move_uploaded_file($_FILES['picture2']['tmp_name'], $myimg2)) {
            $data['picture2'] = $myimg2;
        }
        if ($data['picture2'] == '') {
            $productone->picture2 = $productone->picture2;
        } else {
            $productone->picture2 = $data['picture2'];
        }

        $myimg3 = 'uploads/'. time(). $_FILES['picture3']['name'];
        if (move_uploaded_file($_FILES['picture3']['tmp_name'], $myimg3)) {
            $data['picture3'] = $myimg3;
        }
        if ($data['picture3'] == '') {
            $productone->picture3 = $productone->picture3;
        } else {
            $productone->picture3 = $data['picture3'];
        }

        $myimg4 = 'uploads/'. time(). $_FILES['picture4']['name'];
        if (move_uploaded_file($_FILES['picture4']['tmp_name'], $myimg4)) {
            $data['picture4'] = $myimg4;
        }
        if ($data['picture4'] == '') {
            $productone->picture4 = $productone->picture4;
        } else {
            $productone->picture4 = $data['picture4'];
        }

        R::store($productone);
        header('location: products.php');
        
        exit();
    }

    if (isset($data['do_delete'])) {
        $delete = R::findOne('products', 'id = ?', [$productget]);
        R::trash($delete);
        header('location: products.php');
    }
    if (isset($data['do_delete_pic1'])) {
        $productone->picture1 = NULL;
        R::store($productone);
    }
    if (isset($data['do_delete_pic2'])) {
        $productone->picture2 = NULL;
        R::store($productone);
    }
    if (isset($data['do_delete_pic3'])) {
        $productone->picture3 = NULL;
        R::store($productone);
    }
    if (isset($data['do_delete_pic4'])) {
        $productone->picture4 = NULL;
        R::store($productone);
    }

    $class = $productone->productclass;
    $fclass  = R::findOne( 'productclass', 'id = ?', ["$class"]);  

    $fcat = $productone->category;
    $fcategory  = R::findOne( 'categories', 'id = ?', ["$fcat"]);  

    $product = $productone->productbrand;
    $productbrand  = R::findOne( 'productbrand', 'id = ?', ["$product"]);
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
                        <h1 class="mt-1">
                            <?php 
                            if ($productone->productbrand === NULL) {
                                echo $fclass->productclass_name.' '.$productone->product_title;
                            } else {
                                echo $fclass->productclass_name.' '.$productbrand->productbrand_name.' '.$productone->product_title;
                            }
                            ?>
                    </h1>
                        <div class="card mb-4">
                            <div class="card-header">
                            <form action="productsview.php?key=<?php echo $productget ?>" enctype="multipart/form-data" method="POST">
                            <div class="tabs">
                                <input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
                                <label for="tab-btn-1">Information</label>
                                <input type="radio" name="tab-btn" id="tab-btn-2" value="">
                                <label for="tab-btn-2">Description</label>
                                <input type="radio" name="tab-btn" id="tab-btn-3" value="">
                                <label for="tab-btn-3">Characteristics</label>
                                <input type="radio" name="tab-btn" id="tab-btn-4" value="">
                                <label for="tab-btn-4">Images</label>
                                <div id="content-1">
                                <div class="card-body">
                                    <div>
                                        <label>Product class</label>
                                        <select name="product_class" class="dataTable-input" type="text">
                                        <?php
                                        $productclass = R::findAll('productclass');
                                                foreach ($productclass as $productsclass) {
                                                echo '<option>'. $productsclass->productclass_name .'</option>';
                                                }

                                        ?>
                                        <option value="<?php echo $fclass->productclass_name ?>" selected hidden><?php echo $fclass->productclass_name ?></option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Product brand</label>
                                        <select name="product_brand" class="dataTable-input" type="text">
                                        <?php
                                        $productbrands = R::findAll('productbrand');
                                                foreach ($productbrands as $productsbrand) {
                                                echo '<option>'. $productsbrand->productbrand_name .'</option>';
                                                }
                                                echo '<option>None</option>'
                                        ?>
                                        <option value="<?php echo $productbrand->productbrand_name ?>" selected hidden><?php echo $productbrand->productbrand_name ?></option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Title</label>
                                        <input name="product_title" class="dataTable-input" type="text" value="<?php echo $productone->product_title ?>">
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
                                        <option value="<?php echo $fcategory->category_name ?>" selected hidden><?php echo $fcategory->category_name ?></option>
                                        </select>
                                    </div>
                                    <button name="do_edit" style="margin-top: 10px;" class="btn btn-primary">Edit</button>
                                    <button name="do_delete" style="margin-top: 10px; background-color: red;" class="btn btn-primary">Delete</button>
                                </div>
                                </div>
                                <div id="content-2">
                                    <div>
                                            <label>Description</label>
                                            <input name="description" class="dataTable-input" type="text" value="<?php echo $productone->description ?>">
                                    </div>
                                    <div>
                                            <label>Price</label>
                                            <input name="price" class="dataTable-input" type="text" value="<?php echo $productone->price ?>">
                                            <button name="do_edit" style="margin-top: 10px;" class="btn btn-primary">Edit</button>
                                    </div>
                                </div>
                                <div id="content-3">
                                <div class="form">
                                    <label>Characteristics</label>
                                        <?php
                                            $asd = "SELECT charvalue FROM charprod WHERE product = $productone->id";
                                            $zxc = $link->query($asd);
                                            $qwe = mysqli_fetch_all($zxc, MYSQLI_ASSOC);
                                            $qwe = array_map(function($item) { 
                                                return $item['charvalue'];
                                            },$qwe);
                                            // print_r($qwe);
                                            
                                            $id = $productone->category;
                                            $var = NULL;
                                            $sql = "SELECT * FROM `characteristics` WHERE category = $id";
                                            $result = mysqli_query($link, $sql);
                                            $fid = $row['id'];

                                            $idd = $productone->id;
                                                $charedit = R::find('charprod', 'product = ?', ["$idd"]);
                                                $product_chars = array_map(function($item){
                                                    return $item->charvalue;
                                                }, (array)$charedit);

                                            while($row = $result->fetch_assoc()){
                                                echo  '<input type="text" readonly value="'.$row['characteristic_name'].'">';
                                                $fid = $row['id'];
                                                $values = R::find('characteristicvalue', 'characteristic = '. $fid);
                                                echo '<select name="characteristic[]">';
                                                echo '<option></option>';
                                                foreach($values as $value){
                                                    echo '<option'.(in_array($value->characteristicvalue, $product_chars) ? ' selected' : '').'>'.$value->characteristicvalue.'</option>';
                                                }
                                                echo '</select>';
                                            }
                                            // foreach ($qwe as $res) {
                                            //     while($row = $result->fetch_assoc()){
                                            //         echo  '<input name="inpchar" type="text" readonly value="'.$row['characteristic_name'].'">';
                                            //     }
                                            //     echo '<select name="inpchar" name="characteristic[]">';
                                            //     echo '<option selected hidden>'.$res.'</option>';
                                            //     echo '</select>';
                                            // }

                                            // foreach ($qwe as $res) {
                                            //     while($row = $result->fetch_assoc()){
                                            //     echo  '<input type="text" readonly value="'.$row['characteristic_name'].'">';
                                            //     echo '<select name="characteristic[]">';
                                            //     $fid = $row['id'];
                                            //     $sqls = "SELECT * FROM `characteristicvalue` WHERE characteristic = $fid";
                                            //     $results = mysqli_query($link, $sqls);
                                            //     while($rows = $results->fetch_assoc()) {
                                            //         echo '<option>'.$rows['characteristicvalue'].'</option>';
                                            //     }
                                            //     echo '<option selected hidden>'.$res.'</option>';
                                            //     echo '</select>';
                                            //     }
                                                
                                            // }

                                        ?>
                                    <br><button name="do_edit" style="margin-top: 10px;" class="btn btn-primary">Edit</button>
                                </div>
                                </div>
                                <div id="content-4">
                                <label>Picture 1</label>
                                            <input name="picture1" class="dataTable-input" type="file" value="asd">
                                            <label>Picture 2</label>
                                            <input name="picture2" class="dataTable-input" type="file" value="asd">
                                            <label>Picture 3</label>
                                            <input name="picture3" class="dataTable-input" type="file" value="asd">
                                            <label>Picture 4</label>
                                            <input name="picture4" class="dataTable-input" type="file" value="asd">
                                            <button name="do_edit" style="margin-top: 10px;" class="btn btn-primary">Edit</button>
                                </div>
                                </form>
                                </div>
                            </div>
                            </div>
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
    </body>
</html>

<?php else : ?>
<?php
    header('location: login.php');
?>
<?php endif ?>