<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>%site_name%</title>
    <style>
        /* -------------------------------------
                GLOBAL
        ------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            font-size: 100%;
            line-height: 1.6;
        }

        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
        }

        /* -------------------------------------
                ELEMENTS
        ------------------------------------- */
        a {
            color: #348eda;
        }

        .btn-primary, .btn-secondary {
            text-decoration: none;
            color: #FFF;
            background-color: #348eda;
            padding: 10px 20px;
            font-weight: bold;
            margin: 20px 10px 20px 0;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 25px;
        }

        .btn-secondary {
            background: #aaa;
        }

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        /* -------------------------------------
                TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            line-height: 1.1;
            margin-bottom: 15px;
            color: #000;
            margin: 40px 0 10px;
            line-height: 1.2;
            font-weight: 200;
        }

        h1 {
            font-size: 36px;
        }

        h2 {
            font-size: 28px;
        }

        h3 {
            font-size: 22px;
        }

        p, ul {
            margin-bottom: 10px;
            font-weight: normal;
            font-size: 14px;
        }

        ul li {
            margin-left: 5px;
            list-style-position: inside;
        }
    </style>
</head>

<body style="background-color:#f6f6f6;margin:10px;">
<div style="padding: 20px;">
    <div style="background-color:#fff;border:1px solid #f0f0f0;">
        <div style="color:#000;padding:20px;display:block;text-align:left;">
            %content%
        </div>
    </div>
</div>
<div style="width: 100%;clear:both!important;">
    <div style="padding:20px;max-width:600px;margin:0 auto;display:block;">
        <div style="width:100%;" align="center">
            <p style="font-size:12px;color:#666;"><a href="%site_url%">%site_name%</a></p>
        </div>
    </div>
</div>
</body>
</html>