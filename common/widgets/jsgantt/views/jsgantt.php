<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;

/* @var $model \frontend\models\ViewModel */
/* @var $this yii\web\View */
/* @var $category \backend\models\Category */
$id = isset($id) ? $id : 'jsgantt_' . rand(1, 1000);

$baseUrl = FHtml::currentBaseURL();
if (!isset($CaptionType)) {
    $CaptionType = 'Complete';  // Set to Show Caption (None,Caption,Resource,Duration,Complete)
}

$models = isset($models) ? $models : [];

$fieldID = isset($fieldID) ? $fieldID : ['id', 'pID'];
$fieldName = isset($fieldName) ? $fieldName : ['name', 'title'];
$fieldStartDate = isset($fieldStartDate) ? $fieldStartDate : ['start', 'start_date', 'startDate', 'created_date'];
$fieldEndDate = isset($fieldEndDate) ? $fieldEndDate : ['end', 'end_date', 'endDate', 'modified_date'];
$fieldResource = isset($fieldResource) ? $fieldResource : ['user_name', 'user', 'owner', 'resource'];
$fieldMileStone = isset($fieldMileStone) ? $fieldMileStone : ['is_milestone', 'milestone'];
$fieldLink = isset($fieldLink) ? $fieldLink : ['link_url', 'link', 'url'];
$fieldColor = isset($fieldColor) ? $fieldColor : ['color'];
$fieldCompleted = isset($fieldCompleted) ? $fieldCompleted : ['progress', 'completed'];
$fieldGroup = isset($fieldGroup) ? $fieldGroup : ['pGroup', 'treeview_group'];
$fieldParent = isset($fieldParent) ? $fieldParent : ['parent_id'];
$fieldDepend = isset($fieldDepend) ? $fieldDepend : ['depend_id'];
$fieldNote = isset($fieldNote) ? $fieldNote : ['overview', 'description', 'content'];

if (!isset($data)) {
    $data = [];
    if (isset($models) && is_array($models)) {
        foreach ($models as $model) {
            $item = new \common\base\BaseModelObject();
            $item->pID = FHtml::getFieldValue($model, $fieldID);
            $item->pName = str_replace("'", "", FHtml::getFieldValue($model, $fieldName));
            $item->pStart = FHtml::getFieldValue($model, $fieldStartDate);
            $item->pEnd = FHtml::getFieldValue($model, $fieldEndDate);

//            $item->pPlanStart = FHtml::getFieldValue($model, ['plan_start', 'plan_start']);
//            $item->pPlanEnd = FHtml::getFieldValue($model, ['plan_end', 'plan_end']);
            $item->pStyle = FHtml::getFieldValue($model, $fieldColor, 'gtaskblue');
            $item->pLink = FHtml::getFieldValue($model, $fieldLink);
            $item->pMile = FHtml::getFieldValue($model, $fieldMileStone, 0);
            $item->pRes = FHtml::getFieldValue($model, $fieldResource);
            $item->pComp = FHtml::getFieldValue($model, $fieldCompleted, 0);
            $item->pGroup = FHtml::getFieldValue($model, $fieldGroup, 0);
            $item->pParent = FHtml::getFieldValue($model, $fieldParent, 0);
            $item->pOpen = 1;
            $item->pDepend = FHtml::getFieldValue($model, $fieldDepend, 0);
            $item->pCaption = "";
            $item->pNotes = str_replace("'", "", FHtml::getFieldValue($model, $fieldNote));
//            $item->category = FHtml::getFieldValue($model, ['category', 'category_name', 'category_id']);
//            $item->sector = FHtml::getFieldValue($model, ['type', 'group', 'status']);
            $data[] = $item;
        }
    }
}

if (!isset($dateFormat)) {
    $dateFormat = FHtml::settingDateFormat();  // Set to Show Caption (None,Caption,Resource,Duration,Complete)
}
?>

<?php
$this->registerJsFile("$baseUrl/common/widgets/jsGantt/assets/jsgantt.js", ['position' => \yii\web\View::POS_HEAD]);
$this->registerCssFile("$baseUrl/common/widgets/jsGantt/assets/jsgantt.css");
?>

<div style="position:relative" class="gantt col-md-12" id="<?= $id ?>"></div>


<script type="text/javascript">
//    var g1 = new JSGantt.GanttChart(document.getElementById('<?//= $id  . '1' ?>//'), 'day');
//    JSGantt.parseXML("<?//= $baseUrl ?>///common/widgets/jsGantt/assets/project.xml", g1);
//    g1.Draw();

    var g = new JSGantt.GanttChart(document.getElementById('<?= $id ?>'), 'day');

    if (g.getDivId() != null) {
        g.setCaptionType('<?= $CaptionType ?>');  // Set to Show Caption (None,Caption,Resource,Duration,Complete)
        g.setQuarterColWidth(200);
        g.setDateTaskDisplayFormat('<?= $dateFormat ?>'); // Shown in tool tip box
        g.setDayMajorDateDisplayFormat('mon yyyy - Week ww') // Set format to display dates in the "Major" header of the "Day" view
        g.setWeekMinorDateDisplayFormat('dd mon') // Set format to display dates in the "Minor" header of the "Week" view
        g.setShowTaskInfoLink(1); // Show link in tool tip (0/1)
        g.setShowEndWeekDate(0); // Show/Hide the date for the last day of the week in header for daily view (1/0)
        g.setUseSingleCell(10000); // Set the threshold at which we will only use one cell per table row (0 disables).  Helps with rendering performance for large charts.
        g.setFormatArr('Hour', 'Day', 'Week', 'Month', 'Quarter', 'Year'); // Even with setUseSingleCell using Hour format on such a large chart can cause issues in some browsers

        // Parameters                     (pID, pName,                  pStart,       pEnd,        pStyle,         pLink (unused)  pMile, pRes,       pComp, pGroup, pParent, pOpen, pDepend, pCaption, pNotes, pGantt)
        <?php
            foreach ($data as $model) {
        ?>
        g.AddTaskItem(new JSGantt.TaskItem(
            <?= $model['pID'] ?>,
            '<?= $model['pName'] ?>',
            '<?= $model['pStart'] ?>',
            '<?= $model['pEnd'] ?>',
            '<?= $model['pStyle'] ?>',
            '<?= $model['pLink'] ?>',
            <?= $model['pMile'] ?>,
            '<?= $model['pRes'] ?>',
            <?= $model['pComp'] ?>,
            <?= $model['pGroup'] ?>,
            <?= $model['pParent'] ?>,
            '<?= $model['pOpen'] ?>',
            '<?= $model['pDepend'] ?>',
            '<?= $model['pCaption'] ?>',
            '<?= $model['pNotes'] ?>',
            g));
        <?php } ?>
//        g.AddTaskItem(new JSGantt.TaskItem(1, 'Define Chart API', '', '', 'ggroupblack', '', 0, 'Brian', 0, 1, 0, 1, '', '', 'Some Notes text', g));
//        g.AddTaskItem(new JSGantt.TaskItem(11, 'Chart Object', '2017-02-20', '2017-02-20', 'gmilestone', '', 1, 'Shlomy', 100, 0, 1, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(12, 'Task Objects', '', '', 'ggroupblack', '', 0, 'Shlomy', 40, 1, 1, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(121, 'Constructor Proc', '2017-02-21', '2017-03-09', 'gtaskblue', '', 0, 'Brian T.', 60, 0, 12, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(122, 'Task Variables', '2017-03-06', '2017-03-11', 'gtaskred', '', 0, 'Brian', 60, 0, 12, 1, 121, '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(123, 'Task by Minute/Hour', '2017-03-09', '2017-03-14 12:00', 'gtaskyellow', '', 0, 'Ilan', 60, 0, 12, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(124, 'Task Functions', '2017-03-09', '2017-03-29', 'gtaskred', '', 0, 'Anyone', 60, 0, 12, 1, '123SS', 'This is a caption', null, g));
//        g.AddTaskItem(new JSGantt.TaskItem(2, 'Create HTML Shell', '2017-03-24', '2017-03-24', 'gtaskyellow', '', 0, 'Brian', 20, 0, 0, 1, 122, '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(3, 'Code Javascript', '', '', 'ggroupblack', '', 0, 'Brian', 0, 1, 0, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(31, 'Define Variables', '2017-02-25', '2017-03-17', 'gtaskpurple', '', 0, 'Brian', 30, 0, 3, 1, '', 'Caption 1', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(32, 'Calculate Chart Size', '2017-03-15', '2017-03-24', 'gtaskgreen', '', 0, 'Shlomy', 40, 0, 3, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(33, 'Draw Task Items', '', '', 'ggroupblack', '', 0, 'Someone', 40, 2, 3, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(332, 'Task Label Table', '2017-03-06', '2017-03-09', 'gtaskblue', '', 0, 'Brian', 60, 0, 33, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(333, 'Task Scrolling Grid', '2017-03-11', '2017-03-20', 'gtaskblue', '', 0, 'Brian', 0, 0, 33, 1, '332', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(34, 'Draw Task Bars', '', '', 'ggroupblack', '', 0, 'Anybody', 60, 1, 3, 0, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(341, 'Loop each Task', '2017-03-26', '2017-04-11', 'gtaskred', '', 0, 'Brian', 60, 0, 34, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(342, 'Calculate Start/Stop', '2017-04-12', '2017-05-18', 'gtaskpink', '', 0, 'Brian', 60, 0, 34, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(343, 'Draw Task Div', '2017-05-13', '2017-05-17', 'gtaskred', '', 0, 'Brian', 60, 0, 34, 1, '', '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(344, 'Draw Completion Div', '2017-05-17', '2017-06-04', 'gtaskred', '', 0, 'Brian', 60, 0, 34, 1, "342,343", '', '', g));
//        g.AddTaskItem(new JSGantt.TaskItem(35, 'Make Updates', '2017-07-17', '2017-09-04', 'gtaskpurple', '', 0, 'Brian', 30, 0, 3, 1, '333', '', '', g));


        g.Draw();
    } else {
        console.log("JSGantt DIV ID is null");
    }

</script>



