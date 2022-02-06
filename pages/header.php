<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?php echo $config['title']; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categories
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                           foreach ($allArticleCategory as $cat)
                           {
                               ?>
                               <li><a class="dropdown-item" href="../index.php?category=<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a></li>
                               <?php
                           }
                        ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $config['instagram']; ?>" target="_blank">Instagram</a>
                </li>
                <form id="formSearch" method="get" action="../index.php" class="d-flex align-items-center">
                    <input id="searchQuery" class="form-control me-2" name="q" type="search" placeholder="Search" aria-label="Search" value="">
                    <button class="btn btn-outline-success" type="submit" value="Search">Search</button>
                </form>
            </ul>
            <?php
            if (!isset($_COOKIE['user']))
            {
            ?>
            <div class="btn-group">
                <button type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#ModalIn">Sign In</button>
                <button type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#ModalUp">Sign Up</button>
            </div>
            <?php
            } elseif ($_COOKIE['user'])
            {
               ?>
            <div class="btn-group">
                <form action="" method="post">
                    <button type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#ModalPublicate">Publicate</button>
                </form>
                <form id="logout" action="" method="post">
                    <input name="return" value="logout" type="hidden">
                    <button type="submit" name="submit" value="submit" class="nav-link">Log out</button>
                </form>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</nav>


<!-- ModalIn -->
<div class="modal fade" id="ModalIn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSignIn" class="formSignIn" action="" method="post">
                    <input type="text" class="form-control" name="login" id="loginIn" placeholder="Enter login">
                    <input type="password" class="form-control" name="password" id="passwordIn" placeholder="Enter password">
                    <input type="hidden" class="form-control" name="form" value="In">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="signInButton" type="submit" name="submit" value="submit" class="btn btn-primary">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ModalUp -->
<div class="modal fade" id="ModalUp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSignUp" class="formSignUp" action="" method="post">
                    <input type="text" class="form-control" name="login" id="loginUp" placeholder="Enter login">
                    <input type="password" class="form-control" name="password" id="passwordUp" placeholder="Enter password">
                    <input type="hidden" class="form-control" name="form" value="Up">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Registration</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ModalPublicate -->
<div class="modal fade" id="ModalPublicate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publicate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPublicate" class="formPublicate" action="../includes/validationPublicate.php" method="post" enctype="multipart/form-data">
                    <input type="text" class="form-control" name="title" id="articleTitle" placeholder="Title">
                    <textarea type="textarea" class="form-control" name="text" id="articleText" placeholder="Text" style="margin-top: 10px"></textarea>
                    <select type="select" class="select-category" name="category" id="select-category" style="margin-top: 10px">
                        <?php
                        foreach ($allArticleCategory as $cat)
                        {
                            ?>
                            <option><?php echo $cat['title'];?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <label>IMG</label>
                    <input class="form-control" type="file" name="img" id="loadArticleImg">
                    <input class="form-control" type="hidden" name="form" value="publicate">
                    <div class="modal-footer">
                        <button form="formPublicate" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="buttonPublicate" type="submit" name="submit" class="btn btn-primary">Publicate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

