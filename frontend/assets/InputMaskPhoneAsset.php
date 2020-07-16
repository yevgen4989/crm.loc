<?php


namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class InputMaskPhoneAsset
 * @package frontend\assets
 */
class InputMaskPhoneAsset extends AssetBundle
{
    public $sourcePath = '@bower/inputmask/dist';

    public $js = [
        'inputmask/phone-codes/phone.js'
    ];

    public $depends = [
        'yii\widgets\MaskedInputAsset'
    ];
}