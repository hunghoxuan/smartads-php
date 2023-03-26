<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \mozaframework\atcrud\model\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$rules = $generator->generateSearchRules($tableSchema);
$searchConditions = $generator->generateSearchConditions(false, $tableSchema);
$searchExactConditions = $generator->generateSearchConditions(true, $tableSchema);

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?>Search extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>
{
    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];
        return $arr;
    }

    public static function createNew($values = [], $isSave = false) {
        return <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::createNew();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $andWhere = '')
    {
        return parent::search($params, $andWhere);

/*
        if (!static::isSqlDb()) {
            $models = <?= $searchModelClass ?>::findAll($params);
            return $dataProvider = new FActiveDataProvider([
            'models' => $models,
            ]);
        }

        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        $dataProvider = new FActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        FHtml::loadParams($this, $params);

        if ($searchExact) {
            <?= implode("\n        ", $searchExactConditions) ?>
        } else {
            <?= implode("\n        ", $searchConditions) ?>
        }

        if (!empty($andWhere))
            $query->andWhere($andWhere);

        $params = $this->getDefaultFindParams();
        if (!empty($params))
            $query->andWhere($params);

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
*/
    }
}