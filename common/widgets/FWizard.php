<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use common\components\FHtml;
use drsdre\wizardwidget\WizardWidget;
use drsdre\wizardwidget\WizardWidgetAsset;
use iutbay\yii2kcfinder\KCFinderAsset;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class FWizard extends WizardWidget
{
    const TYPE_ROUND_ICON = 'icon';
    const TYPE_TAB = 'tab';

    public $display_type = self::TYPE_TAB;
    public $style_sheets = ".wizard: { padding:50px; } 
        .tab-content {padding: 15px !important;} 
        .wizard .nav-tabs {margin: 0px !important; border: none !important; background-color: #e9edef !important;} 
        .wizard .nav-tabs > li a {width: auto !important; height: auto !important; font-size: 150% !important; text-align: center !important; } 
        .wizard li.active:after {border: none !important;}
        .wizard .nav-tabs > li.active {background-color:white !important; border:none !important; } 
        .wizard .nav-tabs > li.active> a {text-decoration:none !important; } 
        .wizard .nav-tabs > li > a::after {margin-bottom: 50px !important; }";

    /**
     * @var array default button configuration
     */
    public $default_buttons = [
        'prev' => ['title' => 'Back', 'options' => [ 'class' => 'btn btn-default', 'type' => 'button']],
        'start' => ['title' => 'Start', 'options' => [ 'class' => 'btn btn-warning', 'type' => 'button']],
        'next' => ['title' => 'Continue', 'options' => [ 'class' => 'btn btn-primary', 'type' => 'button']],
        'save' => ['title' => 'Complete', 'options' => [ 'class' => 'btn btn-success', 'type' => 'button']],
        'skip' => ['title' => 'Skip', 'options' => [ 'class' => 'btn btn-default', 'type' => 'button']],
    ];

    public $saveButton = '';

    public $buttons = [];

    /**
     * Main entry to execute the widget
     */
    public function run() {

        if (empty($this->buttons) && is_array($this->buttons))
            $this->buttons = $this->default_buttons;

        if (!empty($this->saveButton)) {
            $this->buttons['save']['html'] = $this->saveButton;
        }

        WizardWidgetAsset::register($this->getView());

        // Wizard line calculation
        $step_count = count($this->steps)+($this->complete_content?1:0);
        $wizard_line_distribution = round(100 / $step_count); // Percentage
        $wizard_line_width = round(100 - $wizard_line_distribution); // Percentage
        $wizard_line = '';

        $tab_content = '';

        // Navigation tracker
        end($this->steps);
        $last_id = key($this->steps);

        $first = true;
        $class = '';

        foreach ($this->steps as $id => $step) {
            if (is_string($step)) {
                $step = [
                    'icon' => '',
                    'title' => 'Step ' . $id,
                    'content' => $step
                ];
            }

            if (!key_exists('icon', $step))
                $step['icon'] = '';


            if (!key_exists('title', $step))
                $step['title'] = '';

            // Current or fist step is active, next steps are inactive (previous steps are available)
            if ($id == $this->start_step or (is_null($this->start_step) && $class == '')) {
                $class = 'active';
            }  else {
                $class = !empty($this->buttons) ? 'disabled' : 'a';
            }

            $text = $this->display_type == self::TYPE_ROUND_ICON ? "<span class='round-tab'><i class='" . $step['icon'] . "'></i></span>" : "<span class='round-tab1'><i class='" . $step['icon'] . "'></i>&nbsp;&nbsp;" . $step['title'] . "</span>";

            // Add icons to the wizard line
            $wizard_line .= Html::tag(
                'li',
                Html::a($text, '#step'.$id, [
                    'data-toggle' => 'tab',
                    'aria-controls' => 'step'.$id,
                    'role' => 'tab',
                    'title' => $step['title'],
                ]),
                array_merge(
                    [
                        'role' => 'presentation',
                        'class' => $class,
                        'style' => ['width' => $wizard_line_distribution.'%']
                    ],
                    isset($step['options']) ? $step['options'] : []
                )
            );

            // Setup tab content
            $tab_content .= '<div class="tab-pane '.$class.'" role="tabpanel" id="step'.$id.'">';
            $tab_content .= $step['content'];

            // Setup navigation buttons
            $buttons = [];
            $button_id = "{$this->id}_step{$id}_";
            if (!$first) {
                // Show previous button except on first step
                $buttons[] = $this->navButton('prev', $step, $button_id);
            }
            if (array_key_exists('skippable', $step) and $step['skippable'] === true) {
                // Show skip button if specified
                $buttons[] = $this->navButton('skip', $step, $button_id);
            }
            if ($id == $last_id) {
                // Show save button on last step
                $buttons[] = $this->navButton('save', $step, $button_id);
            } else if ($id == 0) {
                // On all previous steps show next button
                $buttons[] = $this->navButton('start', $step, $button_id);
            } else {
                // On all previous steps show next button
                $buttons[] = $this->navButton('next', $step, $button_id);
            }

            if (!empty($this->buttons))
                $tab_content .= '<div class="clearfix" style="border-bottom: 1px solid #e9edef; padding-bottom:20px; margin-bottom:20px"></div>';

            // Add buttons to tab content
            $tab_content .= Html::ul($buttons, ['class' => 'list-inline pull-right', 'encode' => false]);

            // Finish tab
            $tab_content .= '</div>';

            $first = false;
        }

        // Add a completed step if specified
        if ($this->complete_content) {
            $class = 'disabled';

            // Check if completed tab is set as start_step
            if ($this->start_step == 'completed') {
                $class = 'active';
            }

            $text = $this->display_type == self::TYPE_ROUND_ICON ? "<span class='round-tab'><i class=\"glyphicon glyphicon-ok\"></i></span>" : 'Completed';

            // Add completed icon to wizard line
            $wizard_line .= Html::tag(
                'li',
                Html::a($text, '#complete', [
                    'data-toggle' => 'tab',
                    'aria-controls' => 'complete',
                    'role' => 'tab',
                    'title' => 'Complete',
                ]),
                [
                    'role' => 'presentation',
                    'class' => $class,
                    'style' => ['width' => $wizard_line_distribution.'%']
                ]
            );

            $tab_content .= '<div class="tab-pane '.$class.'" role="tabpanel" id="complete">'.$this->complete_content.'</div>';
        }

        // Start widget
        echo '<div class="wizard" id="'.$this->id.'">';

        $css1 = $this->display_type == self::TYPE_ROUND_ICON ? "connecting-line1" : '';
        // Render the steps line
        echo '<div class="wizard-inner"><div class="'. $css1 . '" style="width:'.$wizard_line_width.'%"></div>';
        echo '<ul class="nav nav-tabs" role="tablist">'.$wizard_line.'</ul>';
        echo '</div>';

        // Render the content tabs
        echo '<div class="tab-content">'.$tab_content.'</div>';

        // Finalize the content tabs
        echo '<div class="clearfix"></div>';

        // Finish widget
        echo '</div>';

        $this->getView()->registerCss($this->style_sheets);

    }

    /**
     * Generate navigation button
     *
     * @param string $button_type prev|skip|next\save
     * @param array $step step configuration
     * @param string $button_id
     *
     * @return string
     */
    protected function navButton($button_type, $step, $button_id) {
        if ($this->buttons === false)
            return '';

        // Setup a unique button id
        $options = ['id' => $button_id.$button_type];

        // Apply default button configuration if defined
        if (isset($this->buttons[$button_type]['options'])) {
            $options = array_merge($options, $this->buttons[$button_type]['options']);
        }

        // Apply step specific button configuration if defined
        if (isset($step['buttons'][$button_type]['options'])) {
            $options = array_merge($options, $step['buttons'][$button_type]['options']);
        }

        // Add navigation class
        if ($button_type == 'prev') {
            $options['class'] = $options['class'] . ' prev-step';
        } else {
            $options['class'] = $options['class'] . ' next-step';
        }

        if ($button_type == 'save' && !empty($this->saveButton)) //final
            return $this->saveButton;

        // Display button
        if (isset($step['buttons'][$button_type]['html'])) {
            return $step['buttons'][$button_type]['html'];
        } elseif (isset($step['buttons'][$button_type]['title'])) {
            return Html::button(FHtml::t('common', $step['buttons'][ $button_type ]['title']), $options);
        } else {
            return Html::button(FHtml::t('common',$this->buttons[ $button_type ]['title']), $options);
        }
    }

}