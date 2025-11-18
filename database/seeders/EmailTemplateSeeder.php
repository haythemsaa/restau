<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Bienvenue Nouveau Client',
                'subject' => 'Bienvenue chez {{restaurant_name}} !',
                'content' => '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 40px; text-align: center; }
        .content { padding: 30px; }
        .button { background: #6366f1; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Bienvenue {{customer_name}} !</h1>
    </div>
    <div class="content">
        <p>Nous sommes ravis de vous accueillir chez <strong>{{restaurant_name}}</strong>.</p>
        <p>En tant que nouveau client, profitez de <strong>10% de réduction</strong> sur votre prochaine visite avec le code: <strong>BIENVENUE10</strong></p>
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{reservation_link}}" class="button">Réserver une table</a>
        </p>
        <p>À très bientôt,<br>L\'équipe {{restaurant_name}}</p>
    </div>
</body>
</html>',
                'variables' => json_encode(['customer_name', 'restaurant_name', 'reservation_link']),
                'category' => 'welcome',
            ],
            [
                'name' => 'Client Inactif - Réengagement',
                'subject' => '{{customer_name}}, vous nous manquez !',
                'content' => '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 40px; text-align: center; }
        .content { padding: 30px; }
        .offer { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0; }
        .button { background: #f59e0b; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>😢 Vous nous manquez {{customer_name}} !</h1>
    </div>
    <div class="content">
        <p>Cela fait {{days_since_visit}} jours que nous ne vous avons pas vu...</p>
        <div class="offer">
            <h3>🎁 Offre Spéciale de Retour</h3>
            <p><strong>15% de réduction</strong> sur votre prochain repas</p>
            <p>Code: <strong>RETOUR15</strong></p>
            <p>Valable jusqu\'au {{expiry_date}}</p>
        </div>
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{reservation_link}}" class="button">Réserver maintenant</a>
        </p>
        <p>Nous avons hâte de vous revoir,<br>L\'équipe {{restaurant_name}}</p>
    </div>
</body>
</html>',
                'variables' => json_encode(['customer_name', 'days_since_visit', 'expiry_date', 'restaurant_name', 'reservation_link']),
                'category' => 'winback',
            ],
            [
                'name' => 'Programme VIP',
                'subject' => '👑 Félicitations {{customer_name}}, vous êtes VIP !',
                'content' => '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 40px; text-align: center; }
        .content { padding: 30px; }
        .benefits { background: #ecfdf5; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .benefit-item { padding: 10px 0; border-bottom: 1px solid #d1fae5; }
        .button { background: #10b981; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👑 Bienvenue dans le Club VIP !</h1>
    </div>
    <div class="content">
        <p>Cher {{customer_name}},</p>
        <p>Grâce à votre fidélité exceptionnelle, vous accédez au <strong>statut VIP</strong> chez {{restaurant_name}} !</p>

        <div class="benefits">
            <h3>🎁 Vos Avantages VIP :</h3>
            <div class="benefit-item">✓ <strong>20% de réduction</strong> permanente</div>
            <div class="benefit-item">✓ Réservations <strong>prioritaires</strong></div>
            <div class="benefit-item">✓ Accès aux <strong>événements exclusifs</strong></div>
            <div class="benefit-item">✓ Dégustation <strong>gratuite</strong> chaque mois</div>
            <div class="benefit-item">✓ Menu <strong>personnalisé</strong> sur demande</div>
        </div>

        <p>Votre score de fidélité: <strong>{{rfm_score}}/15</strong></p>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{reservation_link}}" class="button">Profiter de mes avantages</a>
        </p>

        <p>Merci pour votre confiance,<br>L\'équipe {{restaurant_name}}</p>
    </div>
</body>
</html>',
                'variables' => json_encode(['customer_name', 'restaurant_name', 'rfm_score', 'reservation_link']),
                'category' => 'vip',
            ],
            [
                'name' => 'Nouveau Menu',
                'subject' => '🍽️ Découvrez notre nouveau menu {{season}}',
                'content' => '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); color: white; padding: 40px; text-align: center; }
        .content { padding: 30px; }
        .dishes { display: grid; gap: 20px; margin: 30px 0; }
        .dish { background: #f8fafc; border-radius: 8px; padding: 15px; }
        .button { background: #8b5cf6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍽️ Nouveau Menu {{season}}</h1>
    </div>
    <div class="content">
        <p>Bonjour {{customer_name}},</p>
        <p>Notre chef a créé des plats exceptionnels pour célébrer la saison !</p>

        <div class="dishes">
            <div class="dish">
                <h4>🥗 Entrée</h4>
                <p>{{entree_name}}</p>
                <p><small>{{entree_description}}</small></p>
            </div>
            <div class="dish">
                <h4>🍖 Plat Principal</h4>
                <p>{{main_name}}</p>
                <p><small>{{main_description}}</small></p>
            </div>
            <div class="dish">
                <h4>🍰 Dessert</h4>
                <p>{{dessert_name}}</p>
                <p><small>{{dessert_description}}</small></p>
            </div>
        </div>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{menu_link}}" class="button">Voir le menu complet</a>
        </p>

        <p>À bientôt,<br>L\'équipe {{restaurant_name}}</p>
    </div>
</body>
</html>',
                'variables' => json_encode(['customer_name', 'season', 'entree_name', 'entree_description', 'main_name', 'main_description', 'dessert_name', 'dessert_description', 'restaurant_name', 'menu_link']),
                'category' => 'promotional',
            ],
            [
                'name' => 'Anniversaire Client',
                'subject' => '🎂 Joyeux anniversaire {{customer_name}} !',
                'content' => '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%); color: white; padding: 40px; text-align: center; }
        .content { padding: 30px; text-align: center; }
        .gift { background: #fce7f3; border-radius: 16px; padding: 30px; margin: 30px 0; }
        .button { background: #ec4899; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎂🎉 Joyeux Anniversaire !</h1>
    </div>
    <div class="content">
        <h2>Cher {{customer_name}},</h2>
        <p style="font-size: 18px;">Toute l\'équipe de {{restaurant_name}} vous souhaite un excellent anniversaire !</p>

        <div class="gift">
            <h3>🎁 Votre Cadeau d\'Anniversaire</h3>
            <p style="font-size: 20px; font-weight: bold; color: #ec4899;">Dessert Offert</p>
            <p>+ 25% de réduction sur votre addition</p>
            <p>Valable pendant tout le mois</p>
            <p>Code: <strong>ANNIVERSAIRE{{year}}</strong></p>
        </div>

        <p style="margin: 30px 0;">
            <a href="{{reservation_link}}" class="button">Réserver pour fêter ça</a>
        </p>

        <p>Profitez bien de votre journée spéciale !<br>L\'équipe {{restaurant_name}}</p>
    </div>
</body>
</html>',
                'variables' => json_encode(['customer_name', 'restaurant_name', 'year', 'reservation_link']),
                'category' => 'special_occasion',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}
