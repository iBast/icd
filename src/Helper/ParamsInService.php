<?php


namespace App\Helper;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function in_array;

/**
 * Récupération des paramètres présents dans le fichier config/service.yaml
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class ParamsInService
{
    public const APP_ENROLLMENT_STATUS_NEW = 'app.enrollment.status.new';
    public const APP_ENROLLMENT_STATUS_PENDING = 'app.enrollment.status.pending';
    public const APP_ENROLLMENT_STATUS_DONE = "app.enrollment.status.done";
    public const APP_ENROLLMENT_STATUS_ARRAY = 'app.enrollment.status.array';


    /** @var ParameterBagInterface */
    private $params;

    /** @var array $datas */
    private $datas = [];

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->datas = [
            self::APP_ENROLLMENT_STATUS_NEW,
            self::APP_ENROLLMENT_STATUS_PENDING,
            self::APP_ENROLLMENT_STATUS_DONE,
            self::APP_ENROLLMENT_STATUS_ARRAY,
        ];
    }

    /**
     * Récupère la valeur paramètre présente dans le fichiers config/services.yaml.
     * Utiliser les constantes présentes dans cette classe
     *
     * @param string $param_name
     * @return string
     */
    public function get(string $param_name)
    {
        if (!in_array($param_name, $this->datas)) {
            throw new InvalidArgumentException('Ce paramètre est inconnu : ' . $param_name);
        }

        return $this->params->get($param_name);
    }
}
