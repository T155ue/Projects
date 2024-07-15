<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UserPage</title>
    <meta name="title" content="UserPage">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="/sdp-assignment/User/viewUser.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <main>
        <header class="flex pt-5 pl-5">

            <a href="../" class="back-btn">Back</a>

        </header>
        <article>
            <section class="">
                <div class="flex flex-row mt-[10rem] ml-[20rem] items-center gap-x-10 h-[20vh]">

                    <div class="">
                        <!--Gets the user image-->
                        <img src="" width="300" height="300" alt="Profile Pics" class=" rounded-full">
                    </div>
                    <div class="w-[30%] ml-[5rem]">
                        <?php if ($user['IsCompany'] == 1) { ?>
                            <span class="label-large section-subtitle">CSS Topia Company</span>
                        <?php } else { ?>
                            <span class="label-large section-subtitle">CSS Topia User</span>
                        <?php } ?>
                        <div class="flex flex-row gap-x-5 items-center">
                            <h1 class="display-small"><?php echo $user['username'] ?></h1>
                            <h1 class="text-2xl mt-6 text-gray-500"><?php echo $user['name'] ?></h1>
                            <h1 class="text-xl mt-6 text-gray-500">ID: <?php echo $user['userid'] ?></h1>
                        </div>



                        <p class="w-[50%]">
                            <?php echo $user['biography'] ?>
                        </p>

                    </div>
                    <!--This need to use the php to changed the label to followed and icon when isFollowed-->
                    <div class="flex flex-row gap-x-10 gap-y-10">
                        <div class="flex flex-col gap-x-10 gap-y-10">
                            <a href="" class="chip">
                                <i class='bx bxs-user-x' style="font-size: 25px;"></i>
                                <span class="label-large">Add</span>
                                <div class="state-layer"></div>
                            </a>
                            <a href="" class="chip">
                                <i class='bx bx-mail-send' style="font-size: 20px;"></i>
                                <span class="label-large">Inbox</span>
                                <div class="state-layer"></div>
                            </a>
                        </div>

                        <div class="flex flex-col gap-x-10 gap-y-10">
                            <a href="mailto:<?php echo $user['email'] ?>" class="chip">
                                <span class="material-symbols-outlined" aria-hidden="true">Mail</span>
                                <span class="label-large"><?php echo $user['email'] ?></span>
                                <div class="state-layer"></div>
                            </a>
                            <a href="tel:+60111111111" class="chip">
                                <span class="material-symbols-outlined" aria-hidden="true">Call</span>
                                <span class="label-large"><?php echo $user['phone'] ?></span>
                                <div class="state-layer"></div>
                            </a>
                        </div>

                    </div>

                </div>

            </section>
            <section class="mt-[10rem]">
                <div class="article">
                    <div class="about-card drop-shadow-md">
                        <h2 class="card-title title-medium">About</h2>
                        <ul class="about-list">
                            <li class="list-item">
                                <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                                <span class="label-medium"><?php echo $user['state'] ?></span>
                            </li>
                            <li class="list-item">
                                <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                                <span class="label-medium"><?php echo $user['facebook'] ?></span>
                            </li>
                            <li class="list-item">
                                <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                                <span class="label-medium"><?php echo $user['instagram'] ?></span>
                            </li>
                            <li class="list-item">
                                <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                                <span class="label-medium"><?php echo $user['linkedIn'] ?></span>
                            </li>
                            <li class="list-item">
                                <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
                                <span class="label-medium"><?php echo $user['twitter'] ?></span>
                            </li>


                        </ul>
                    </div>
                    <div>
                        <div class="project">
                            <button class="tab-btn active" data-tab-btn="project">
                                <span class="tab-text title-small">Projects</span>>
                            </button>
                        </div>
                        <section class="section tab-content" data-tab-content="project"></section>
                        <div class="">
                            <div class="grid grid-cols-2 overflow-y-scroll gap-x-5 gap-y-5 h-[50vh] px-5">
                                <?php foreach ($projects as $project) { ?>
                                    <div class="border-2 border-gray-500 rounded-md drop-shadow-md p-10 hover:bg-gray-300 transition">
                                        <iframe srcdoc="<html lang=' en'>
                                                                <style>
                                                                    <?php echo $project['component_css'] ?>
                                                                    
                                                                </style>
                                                                <body>
                                                                <?php echo $project['component_html'] ?>
                                                                </body>
                                        </html>" class="w-full h-64" frameborder="0">
                                        </iframe>
                                        <div>
                                            <h1 class="text-3xl font-bold"><?php echo $project['component_name'] ?></h1>

                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
    </main>
    <footer class="footer">
        <div class="container">
            <p class="body-medium">CSS Topia</p>
        </div>
    </footer>
</body>

</html>