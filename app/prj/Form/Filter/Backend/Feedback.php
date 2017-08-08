<?php
DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_Feedback extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        $this->smarty->assign(
            array(
                'feedback_types' => DxFactory::invoke('DomainObjectModel_Feedback', 'getFeedbackTypes'),
            )
        );

        return $this->smarty->fetch('backend/filter/feedback.tpl.php');
    }
}