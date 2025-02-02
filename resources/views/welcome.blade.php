<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escuela de Taekwondo "Juan José Rojas"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <style>
        :root {
            --primary-red: #ff3b3b;
            --dark-black: #1a1a1a;
            --gradient-red: linear-gradient(135deg, #ff3b3b 0%, #d10000 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)),
                url('/img/escuela.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .navbar {
            background: rgba(26, 26, 26, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            position: relative;
            padding: 0.5rem 1rem;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--primary-red);
            transition: all 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }

        .btn-login {
            background: var(--gradient-red);
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 59, 59, 0.3);
        }

        .section-title {
            color: var(--primary-red);
            font-weight: 800;
            position: relative;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--gradient-red);
            border-radius: 2px;
        }

        .card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .carousel-item img {
            height: 600px;
            object-fit: cover;
            border-radius: 20px;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            padding: 20px;
        }

        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 8px;
        }

        .footer {
            background: var(--dark-black);
            color: white;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-red), transparent);
        }

        .social-icon {
            color: white;
            font-size: 1.8rem;
            margin: 0 15px;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            color: var(--primary-red);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .carousel-item img {
                height: 400px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/img/taek.jpg" alt="Logo" height="45">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#galeria">Galería</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#vision">Visión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#proposito">Propósito</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-login text-white ms-3">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center" id="inicio">
        <div class="container text-center">
            <h1 class="display-2 fw-bold mb-4" data-aos="fade-up">Escuela de Taekwondo "Juan José Rojas"</h1>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="200">
                Formando campeones en el tatami y en la vida
            </p>
            <a href="{{ route('login') }}" class="btn btn-login btn-lg text-white" data-aos="fade-up"
                data-aos-delay="400">
                Únete a Nosotros
            </a>
        </div>
    </section>

    <!-- Galería Section -->
    <section class="py-5" id="galeria">
        <div class="container">
            <h2 class="section-title text-center" data-aos="fade-up">Nuestra Galería</h2>
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up"
                data-aos-delay="200">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                        class="active"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="/img/t1.jpg" class="d-block w-100" alt="Taekwondo 1">
                        <div class="carousel-caption">
                            <h5>Excelencia en el Tatami</h5>
                            <p>Formación de alto rendimiento para nuestros atletas</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/img/t2.jpg" class="d-block w-100" alt="Taekwondo 2">
                        <div class="carousel-caption">
                            <h5>Disciplina y Dedicación</h5>
                            <p>Valores fundamentales en nuestra escuela</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/img/t3.jpg" class="d-block w-100" alt="Taekwondo 3">
                        <div class="carousel-caption">
                            <h5>Formación Integral</h5>
                            <p>Desarrollo físico y mental para nuestros estudiantes</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/img/t4.jpg" class="d-block w-100" alt="Taekwondo 4">
                        <div class="carousel-caption">
                            <h5>Competencia y Superación</h5>
                            <p>Preparación para eventos nacionales e internacionales</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/img/t5.jpg" class="d-block w-100" alt="Taekwondo 5">
                        <div class="carousel-caption">
                            <h5>Espíritu de Equipo</h5>
                            <p>Unidos en el camino hacia la excelencia</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Visión Section -->
    <section class="py-5 bg-light" id="vision">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title" data-aos="fade-up">Nuestra Visión</h2>
                    <div class="card p-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <p class="mb-3">
                            La visión de nuestra escuela está basada en ser reconocidos como una institución líder en la
                            formación
                            de atletas de Taekwondo de alto rendimiento, capaces de destacar a nivel nacional e
                            internacional.
                        </p>
                        <p class="mb-3">
                            Buscamos ser un referente en la promoción de la práctica deportiva como medio para
                            fortalecer el carácter,
                            la autoestima y la salud de nuestros estudiantes, consolidando así su participación activa
                            en la sociedad
                            como ciudadanos íntegros y comprometidos.
                        </p>
                        <p class="mb-0">
                            Nos enfocamos en la inclusión a través del taekwondo, brindando oportunidades a personas que
                            por sus
                            capacidades cognitivas son excluidas de otros deportes, utilizando este arte marcial como
                            una
                            terapia ocupacional desde temprana edad.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Propósito Section -->
    <section class="py-5" id="proposito">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title" data-aos="fade-up">Nuestro Propósito</h2>
                    <div class="card p-4" data-aos="fade-up" data-aos-delay="200">
                        <p class="mb-3">
                            La Escuela de Taekwondo "Juan José Rojas" tiene como propósito principal proporcionar un
                            ambiente
                            inclusivo y seguro donde todos los alumnos puedan desarrollar sus habilidades atléticas y
                            personales.
                        </p>
                        <p class="mb-0">
                            Nuestro objetivo es formar campeones no solo en el tatami, sino también en la vida,
                            inculcando valores
                            como la humildad, la solidaridad y el trabajo en equipo. A través de una enseñanza
                            profesional y dedicada,
                            buscamos inspirar a nuestros estudiantes a alcanzar su máximo potencial, guiándolos en el
                            camino hacia
                            el éxito deportivo y personal.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container text-center">
            <div class="mb-3">
                <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            </div>
            <p class="mb-0">&copy; 2025 Escuela de Taekwondo "Juan José Rojas". Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>

</html>
