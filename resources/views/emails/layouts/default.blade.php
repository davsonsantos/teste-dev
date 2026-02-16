<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" type="text/css">
    <!--[if mso]>
      <noscript>
        <xml>
          <o:OfficeDocumentSettings
            xmlns:o="urn:schemas-microsoft-com:office:office"
          >
            <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
        </xml>
      </noscript>
      <style>
        td,
        th,
        div,
        p,
        a,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
          font-family: "Segoe UI", sans-serif;
          mso-line-height-rule: exactly;
        }
      </style>
    <![endif]-->
    <title>{{ config('app.name') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        .hover-bg-blue-600:hover {
            background-color: #14532d !important;
        }

        .hover-underline:hover {
            text-decoration: underline !important;
        }

        @media (max-width: 600px) {
            .sm-w-full {
                width: 100% !important;
            }

            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }

            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }

            .sm-leading-32 {
                line-height: 32px !important;
            }
        }

        @media (prefers-color-scheme: dark) {
            .dark-mode-bg-gray-999 {
                background-color: #e6e7e8 !important;
            }

            .dark-mode-bg-gray-989 {
                background-color: #ffffff !important;
            }

            .dark-mode-text-gray-979 {
                color: #3d3d3d !important;
            }

            .dark-mode-text-white {
                color: #ffffff !important;
            }
        }

        a:link {
            color: #14532d;
            text-decoration: none;
        }

        /* visited link */
        a:visited {
            color: green;
            text-decoration: none;
        }

        /* mouse over link */
        a:hover {
            color: #052e16;
            text-decoration: none;
        }

        /* selected link */
        a:active {
            color: blue;
        }
    </style>
</head>

<body class="dark-mode-bg-gray-999"
    style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #f3f4f6;">
    <div role="article" aria-roledescription="email" aria-label="Welcome!" lang="en">
        <table class="sm-w-full" align="center" style="width: 600px;" cellpadding="0" cellspacing="0"
            role="presentation">
            <tr>
                <td class="sm-py-32 sm-px-24" style="padding: 48px; text-align: center;">
                    <a href="{{ url('/') }}" style="display: inline-block;">
                        TESTE DEV
                    </a>
                </td>
            </tr>
        </table>
        <table style="width: 100%; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;"
            cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" class="dark-mode-bg-gray-999" style="background-color: #f3f4f6;">
                    <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td align="center" class="sm-px-24">
                                <table style="margin-bottom: 48px; width: 100%;" cellpadding="0" cellspacing="0"
                                    role="presentation">
                                    <tr>
                                        <td class="dark-mode-bg-gray-989 dark-mode-text-gray-979 sm-px-24"
                                            style="background-color: #ffffff; padding: 48px; text-align: left; font-size: 16px; line-height: 24px; color: #1f2937;">
                                            @yield('content')
                                            <table style="width: 100%;" cellpadding="0" cellspacing="0"
                                                role="presentation">
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width: 100%; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;"
            cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td
                    style="padding-left: 24px; padding-right: 24px; text-align: center; font-size: 14px; color: #4b5563;">
                    <span style="margin: 0; margin-bottom: 4px; text-transform: uppercase;">
                        © @php echo date('Y'); @endphp {{ config('app.name') }}. Todos os direitos reservados.
                    </span>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>