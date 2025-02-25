<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia - {{ $athlete->full_name }}</title>
    <style>
        /* Estilos generales */
        @page {
            margin: 1.5cm; /* Márgenes reducidos */
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Encabezado */
        .header {
            margin-bottom: 20px;
            overflow: hidden; /* Limpiar floats */
        }

        .logo {
            width: 100px; /* Tamaño del logo */
            float: left; /* Logo a la izquierda */
            margin-right: 20px; /* Espacio entre el logo y el texto */
        }

        .school-name {
            font-size: 18px; /* Tamaño de fuente reducido */
            font-weight: bold;
            margin-bottom: 5px;
        }

        .contact-info {
            font-size: 9px; /* Tamaño de fuente reducido */
            margin-bottom: 5px;
        }

        .affiliations {
            font-size: 8px; /* Tamaño de fuente reducido */
            font-style: italic;
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        /* Título de la constancia */
        .title {
            text-align: center;
            font-size: 16px; /* Tamaño de fuente reducido */
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }

        /* Contenido de la constancia */
        .content {
            text-align: justify;
            font-size: 12px; /* Tamaño de fuente reducido */
            margin-bottom: 20px;
        }

        /* Firmas */
        .signatures-container {
            width: 100%;
            margin-top: 80px; /* Más espacio hacia arriba */
            overflow: hidden; /* Limpiar floats */
        }

        .signature {
            width: 30%; /* Ancho de cada firma */
            display: inline-block; /* Alinear horizontalmente */
            text-align: center;
            margin-bottom: 10px;
            vertical-align: top; /* Alinear firmas en la parte superior */
        }

        .signature-line {
            width: 120px; /* Línea para la firma */
            border-top: 1px solid #000;
            margin: 5px auto;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10px; /* Tamaño de fuente reducido */
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 9px; /* Tamaño de fuente reducido */
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <!-- Encabezado con logo y texto -->
    <div class="header">
        <!-- Logo a la izquierda -->
        <img src="{{ public_path('img/taek.jpg') }}" alt="Logo" class="logo">
        <!-- Encabezado a la derecha -->
        <div>
            <div class="school-name">
                ESCUELA DE TAE-KWON-DO<br>
                "JUAN JOSÉ ROJAS"
            </div>
            <div class="contact-info">
                0412-0911798 / 0416-6835193<br>
                COMPLEJO POLIDEPORTIVO SIMÓN BOLÍVAR,<br>
                AV INTERCOMUNAL, BNA, EDO ANZOÁTEGUI<br>
                juanjosertkd@gmail.com<br>
                rreinosa86@gmail.com<br>
                RIF: V-06643926-3
            </div>
            <div class="affiliations">
                AFILIADA A LA ESCUELA DE ARTES MARCIALES HONG KI KIM DE PUERTO LA CRUZ<br>
                INSCRITA EN I.N.D, EL IDANZ, LA F.V.T Y LA ASOCIACIÓN DE TAE-KWON-DO DEL EDO ANZOÁTEGUI
            </div>
        </div>
    </div>

    <!-- Título de la constancia -->
    <div class="title">
        CONSTANCIA
    </div>

    <!-- Contenido de la constancia -->
    <div class="content">
        <p>
            Quien suscribe, en su carácter de Presidente y Profesor de la Escuela de
            Taekwondo "Juan José Rojas" adscrita a la División de Prevención del Delito
            del Edo. Anzoátegui que funciona en el Complejo Polideportivo Libertador
            Simón Bolívar, hace constar por medio de la presente que {{ $athlete->gender === 'M' ? 'el atleta' : 'la atleta' }} 
            <strong>{{ $athlete->full_name }}</strong> de {{ $age }} años de edad, 
            {{ $athlete->identity_document ? 'portador(a) de la C.I.: ' . $athlete->identity_document : 'sin cédula de identidad' }} 
            forma parte de nuestra plantilla de atletas.
        </p>
        <p>
            {{ $athlete->gender === 'M' ? 'El mencionado' : 'La mencionada' }} atleta asiste a prácticas regulares 
            de lunes a viernes en horario comprendido de 3:00pm a 6:30pm.
        </p>
        <p>
            Constancia que se expide a solicitud de la parte interesada en Barcelona, a los 
            <strong>{{ date('d') }}</strong> días del mes de <strong>{{ date('m') }}</strong> del año <strong>{{ date('Y') }}</strong>.
        </p>
        <p>
            Se agradece a las autoridades civiles y militares prestar el máximo apoyo a 
            {{ $athlete->gender === 'M' ? 'el' : 'la' }} atleta antes {{ $athlete->gender === 'M' ? 'mencionado' : 'mencionada' }} 
            para su práctica deportiva.
        </p>
    </div>

    <!-- Firmas alineadas horizontalmente -->
    <div class="signatures-container">
        <!-- Firma 1 -->
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">JUAN JOSE ROJAS</div>
            <div class="signature-title">G.M VII DAN</div>
            <div class="signature-title">Presidente</div>
            <div class="signature-title">V-6.643.926</div>
            <div class="signature-title">0412-0911798</div>
        </div>
        <!-- Firma 2 -->
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">CARMEN McCAMPOS</div>
            <div class="signature-title">Secretaria General</div>
            <div class="signature-title">V-6.9M 3171</div>
        </div>
        <!-- Firma 3 -->
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">JOSE VELASQUEZ</div>
            <div class="signature-title">Vice-Presidente</div>
            <div class="signature-title">V-I.1000-153</div>
        </div>
    </div>
</body>
</html>