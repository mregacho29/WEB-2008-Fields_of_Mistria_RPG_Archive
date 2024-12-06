<?php
require('connect.php');
include('functions.php');
include('header.php');

$config = [
    'gallery_name' => 'Screenshot and Media Gallery',
    'title' => 'Fields of Mistria',
    'local_pics' => [
        'pic1' => ['alt' => 'pic1', 'name' => '', 'pic' => 'pic1.png'],
        'pic2' => ['alt' => 'pic2', 'name' => 'Fall Fishing', 'pic' => 'pic2.png'],
        'pic3' => ['alt' => 'pic3', 'name' => 'Winter Conversation', 'pic' => 'pic3.png'],
        'pic4' => ['alt' => 'pic4', 'name' => 'Spring Festival', 'pic' => 'pic4.png'],
        'pic5' => ['alt' => 'pic5', 'name' => 'Blacksmiths Shop', 'pic' => 'pic5.png']
        
    ]
];
?>

<body>
    <main>
        <div class="container py-4 text-center">
            <!-- Breadcrumb Begin -->
            <nav class="breadcrumb-nav py-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <header class="pb-3 mb-5 border-bottom d-flex justify-content-between align-items-center">
                <a href="/" class="d-flex align-items-center text-body-emphasis text-decoration-none">
                <img src="image/categorieslogo1.gif" width="40" height="32" class="me-2" alt="Logo">
                    <span class="fs-4"><?= $config['gallery_name']?></span>
                </a>
            </header>

            <div id="gallery" class="gallery d-flex justify-content-center">
                <?php foreach($config['local_pics'] as $key => $pic): ?>
                    <?php if ($key === 'pic1'): ?>
                        <div class="gallery-item mx-auto">
                            <a href="picture/<?= $pic['pic'] ?>" class="lightbox">
                                <img src="picture/<?= pathinfo($pic['pic'], PATHINFO_FILENAME) ?>_thumbnail.png" alt="<?= $pic['alt'] ?>" class="main-image">
                            </a>
                            <h2><?= $pic['name'] ?></h2>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="row justify-content-center">
                <?php foreach($config['local_pics'] as $key => $pic): ?>
                    <?php if ($key !== 'pic1'): ?>
                        <div class="col-3 col-md-2">
                            <div class="gallery-item">
                                <a href="picture/<?= $pic['pic'] ?>" class="lightbox">
                                    <img src="picture/<?= pathinfo($pic['pic'], PATHINFO_FILENAME) ?>_thumbnail.png" alt="<?= $pic['alt'] ?>" class="thumbnail img-thumbnail">
                                </a>
                                <h2><?= $pic['name'] ?></h2>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <script>
        new LuminousGallery(document.querySelectorAll(".lightbox"));
    </script>
</body>

<?php
include('footer.php');
?>