<?php

class FilaEmailLogTimeLine extends TPage
{
    private static $database = 'databaserede';
    private static $activeRecord = 'FilaEmailLog';
    private static $primaryKey = 'id';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null )
    {
        try
        {
            parent::__construct();

            TTransaction::open(self::$database);

            if(!empty($param['target_container']))
            {
                $this->adianti_target_container = $param['target_container'];
            }

            $this->timeline = new TTimeline;
            $this->timeline->setItemDatabase(self::$database);
            $this->timelineCriteria = new TCriteria;

            if(!empty($param["key"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_fila_emails_id', $param["key"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_fila_emails_id');
            $this->timelineCriteria->add(new TFilter('fila_emails_id', '=', $filterVar));

            $limit = 0;

            $this->timelineCriteria->setProperty('limit', $limit);
            $this->timelineCriteria->setProperty('order', 'id asc');

            $objects = FilaEmailLog::getObjects($this->timelineCriteria);

            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $object->dm_status = call_user_func(function($value, $object, $row)
                    {

                        return DMService::obterMask('dm_status_email', $value);

                    }, $object->dm_status, $object, null);

                    $id = $object->id;
                    $title = "{dm_status}";
                    $htmlTemplate = " {mensagem} ";
                    $date = $object->data_criacao;
                    $icon = 'fa:arrow-left bg-green';
                    $position = 'left';

                    if(empty($positionValue[$object->dm_status]))
                    {
                        $lastPosition = (empty($lastPosition) || $lastPosition == 'right') ? 'left' : 'right';
                        $bg = ($lastPosition == 'left') ? 'bg-green' : 'bg-blue';

                        $positionValue[$object->dm_status]['position'] = $lastPosition;
                        $positionValue[$object->dm_status]['bg'] = $bg;
                        $position = $positionValue[$object->dm_status]['position'];
                        $icon = "fa:arrow-{$lastPosition} {$bg}";
                    }
                    else
                    {
                        $position = $positionValue[$object->dm_status]['position'];
                        $lastPosition = $position;
                        $icon = "fa:arrow-{$lastPosition} {$positionValue[$object->dm_status]['bg']}";
                    }

                    $this->timeline->addItem($id, $title, $htmlTemplate, $date, $icon, $position, $object);

                }
            }

            $this->timeline->setUseBothSides();
            $this->timeline->setTimeDisplayMask('dd/mm/yyyy');
            $this->timeline->setFinalIcon( 'fas:flag-checkered #ffffff #de1414' );

            $container = new TVBox;

            $container->style = 'width: 100%';
            $container->class = 'form-container';

            parent::setTargetContainer('adianti_right_panel');
            $container->style .= '; padding: 0 15px';
            $div = new TElement('div');
            $div->style = 'margin-top: 20px; margin-right: 20px; float: right';

            $btnClose = new TButton('closeCurtain');
            $btnClose->class = 'btn btn-sm btn-default';

            $btnClose->onClick = "Template.closeRightPanel();";
            $btnClose->setLabel("Fechar");
            $btnClose->setImage('fas:times');

            $div->add($btnClose);
            $container->add($div);
            $container->add($this->timeline);

            TTransaction::close();

            parent::add($container);
        }
        catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {

    } 

}

