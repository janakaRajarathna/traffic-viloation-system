<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= e($pageTitle ?? 'Dash Cam') ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 128 128%22><text y=%221.2em%22 font-size=%2296%22>⚫️</text></svg>">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#0061a4",
              "primary-container": "#2196f3",
              "on-primary": "#ffffff",
              "on-surface": "#171c20",
              "on-surface-variant": "#404752",
              "surface": "#f6faff",
              "surface-container-lowest": "#ffffff",
              "surface-container-low": "#f0f4fa",
              "surface-container-high": "#e4e8ee",
              "surface-container-highest": "#dfe3e9",
              "outline": "#707883",
              "outline-variant": "#bfc7d4",
              "error": "#ba1a1a",
              "error-container": "#ffdad6",
              "on-error-container": "#93000a",
              "secondary": "#416084",
              "tertiary": "#904d00",
              "tertiary-fixed": "#ffdcc2",
              "on-tertiary-fixed-variant": "#6d3900",
              "tertiary-container": "#db7900",
              "background": "#f6faff",
              "primary-fixed-dim": "#9ecaff",
              "secondary-fixed-dim": "#a9c9f2",
              "on-secondary-container": "#3c5c7f",
            },
            fontFamily: {
              headline: ["Manrope"],
              body: ["Inter"],
            }
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .bg-login-gradient { background: radial-gradient(circle at top left, #f0f4fa 0%, #f6faff 100%); }
        .primary-cta-gradient { background: linear-gradient(135deg, #0061a4 0%, #2196f3 100%); }
    </style>
    <?= $extraHead ?? '' ?>
</head>
<body class="<?= e($bodyClass ?? 'bg-surface font-body text-on-surface min-h-screen flex flex-col antialiased') ?>">
    <?= $content ?>
</body>
</html>
