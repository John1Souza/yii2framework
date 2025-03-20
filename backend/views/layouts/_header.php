<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-expand-lg navbar-light bg-light shadow-sm',
    ],  
]);
$menuItems = [
    ['label' => 'Create', 'url' => ['/site/index']],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $menuItems[] = [
        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post'
        ]
    ];
};
echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto'],
    'items' => $menuItems,
]);
// if (Yii::$app->user->isGuest) {
//     echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
// } else {
//     echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
//         . Html::submitButton(
//             'Logout (' . Yii::$app->user->identity->username . ')',
//             ['class' => 'btn btn-link logout text-decoration-none']
//         )
//         . Html::endForm();
// }
NavBar::end();
