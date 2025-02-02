<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia - {{ $athlete->full_name }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .contact-info {
            font-size: 12px;
            margin-bottom: 5px;
        }
        .affiliations {
            font-size: 10px;
            font-style: italic;
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
        }
        .content {
            text-align: justify;
            margin: 20px 0;
            font-size: 14px;
        }
        .signature {
            text-align: center;
            margin-top: 100px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }
        .signature-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature-title {
            font-size: 12px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
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
            INSCTRITA EN I.N.D, EL IDANZ, LA F.V.T Y LA ASOCIACIÓN DE TAE-KWON-DO DEL EDO ANZOÁTEGUI
        </div>
    </div>

    <div class="title">
        CONSTANCIA
    </div>

    <div class="content">
        Quien suscribe, en su carácter de Presidente y Profesor de la Escuela de
        Taekwondo "Juan José Rojas" adscrita a la División de Prevención del Delito
        del Edo. Anzoátegui que funciona en La Urbanización Vista Alegre, hace
        constar por medio de la presente que {{ $athlete->gender === 'M' ? 'el atleta' : 'la atleta' }} 
        <strong>{{ $athlete->full_name }}</strong> de {{ $age }} años de edad, 
        {{ $athlete->identity_document ? 'portador(a) de la C.I.: ' . $athlete->identity_document : 'sin cédula de identidad' }} 
        forma parte de nuestra plantilla de atletas.
    </div>

    <div class="content">
        {{ $athlete->gender === 'M' ? 'El mencionado' : 'La mencionada' }} atleta asiste a prácticas regulares 
        de lunes a viernes en horario comprendido de 3:00pm a 6:30pm.
    </div>

    <div class="content">
        Se agradece a las autoridades civiles y militares prestar el máximo apoyo a 
        {{ $athlete->gender === 'M' ? 'el' : 'la' }} atleta antes {{ $athlete->gender === 'M' ? 'mencionado' : 'mencionada' }} 
        para su práctica deportiva.
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <div class="signature-name">JUAN JOSE ROJAS</div>
        <div class="signature-title">G.M VII DAN</div>
        <div class="signature-title">Presidente</div>
        <div class="signature-title">V-6.643.926</div>
        <div class="signature-title">0412-0911798</div>
    </div>
</body>
</html>

