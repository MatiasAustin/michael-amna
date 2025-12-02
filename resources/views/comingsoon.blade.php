<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>
    <link rel="icon" href="media/anm-logo.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        p {
            margin: 0;
            font-weight: 300;
            font-size: 14px;
            text-transform: uppercase;
            }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            font-weight: 300;
            text-transform: uppercase;
        }

        body {
            background-color: #3d1516;
            color: #F3ECDC;
            margin: 0 auto;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            font-family: 'poppins', sans-serif;
            text-align: center;
            padding: 15%;
        }

        .cont-comingsoon {
            margin: 0 auto;
            text-align: center;
            width: 100%;
            height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-image: url(../../public/media/anm-pattern-bg.png);
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            padding: 10%;
        }

        @media (max-width: 768px) {
            body {
                padding: 5%;
            }

            .cont-comingsoon {
                padding: 5%;
            }
        }
    </style>
</head>
<body>

    <div class="cont-comingsoon">
        <h1>FOREVER KHOURY’S</h1>
        <h3>Coming Soon</h3>

        <h2 style="margin-top: 20px">A new chapter begins soon.</h2>
        <p style="margin-top: 10px;">Michael & Amna’s story will be revealed here.</p>

    </div>

</body>
</html>
