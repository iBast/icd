<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Helper;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Récupération des paramètres présents dans le fichier config/service.yaml.
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 */
class ParamsInService
{
    public const APP_ENROLLMENT_NEW = 'app_enrollment_new';
    public const APP_ENROLLMENT_PENDING = 'app_enrollment_pending';
    public const APP_ENROLLMENT_DONE = 'app_enrollment_done';
    public const APP_EMAIL_ADRESS = 'app_email_adress';
    public const APP_EMAIL_NAME = 'app_email_name';
    public const APP_ACCOUNTPREFIX_MEMBER = 'app_accountprefix_member';

    /** @var ParameterBagInterface */
    private $params;

    /** @var array */
    private $datas = [];

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->datas = [
            self::APP_ENROLLMENT_NEW,
            self::APP_ENROLLMENT_PENDING,
            self::APP_ENROLLMENT_DONE,
            self::APP_EMAIL_ADRESS,
            self::APP_EMAIL_NAME,
            self::APP_ACCOUNTPREFIX_MEMBER,
        ];
    }

    /**
     * Récupère la valeur paramètre présente dans le fichiers config/services.yaml.
     * Utiliser les constantes présentes dans cette classe.
     */
    public function get(string $param_name): string
    {
        if (!\in_array($param_name, $this->datas, true)) {
            throw new InvalidArgumentException('Ce paramètre est inconnu : '.$param_name);
        }

        return $this->params->get($param_name);
    }
}
