<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
        <meta name="mit" content="2020-12-29T11:38:21-03:00+169293">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?= $head; ?>
	
	<!-- Bootstrap core CSS -->
        <link href="shared/css/bootstrap.min.css" rel="stylesheet">

        <!-- Animation CSS -->
        <link href="shared/css/animate.min.css" rel="stylesheet">

        <link href="shared/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="shared/fonts/flaticon/font/flaticon.css" rel="stylesheet">
		



        <!-- Custom styles for this template -->
        <link href="shared/css/style.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png"); ?>"/>
    <link rel="stylesheet" href="<?= theme("/assets/style.css"); ?>"/>
</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>


<header class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="" href="index.html">
                    </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="active" title="Home" href="<?= url(); ?>">Home</a></li>
						<!-- <li><a class="" title="Como Funciona" href="<?= url("/sobre"); ?>">Como Funciona</a></li>-->
                        <!--<li><a class="" title="Preços" href="<?= url("/preços"); ?>">Preços</a></li>-->
                        <!--<li><a class="" title="Suporte" href="<?= url("/suporte"); ?>">Suporte</a></li>-->
						<li><a class="" title="" href="<?= url("/work-entrar"); ?>">Login</a></li>
                       
						<!--<li><a class="" title="Entrar" href="<?= url("/entrar"); ?>">Entrar</a></li>-->
			
                        <!--<li><a class="page-scroll" href="/contato">SOLICITE SEU SITE</a></li>-->
                      
                    </ul>
                </div>
            </div>
        </nav>
</header>



<!--CONTENT-->
<main class="main_content">
    <?= $v->section("content"); ?>
</main>






<script async src="https://www.googletagmanager.com/gtag/js?id=UA-53658515-18"></script>
<script src="<?= theme("/assets/scripts.js"); ?>">
</script><?= $v->section("scripts"); ?>
<script src="shared/js/pace.min.js"></script>
<script src="shared/js/bootstrap.min.js"></script>
<script src="shared/js/classie.js"></script>
<script src="shared/js/cbpAnimatedHeader.js"></script>
<script src="shared/js/wow.min.js"></script>
<script src="shared/js/inspinia.js"></script>
<script src="shared/js/bootstrap.min.js"></script>


           
</body>
</html>

