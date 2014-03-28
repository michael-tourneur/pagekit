<?php

namespace Pagekit\Widget;

use Pagekit\Component\Database\ORM\Repository;
use Pagekit\Framework\ApplicationAware;
use Pagekit\Widget\Event\RegisterWidgetEvent;
use Pagekit\Widget\Model\WidgetInterface;

class WidgetProvider extends ApplicationAware
{
    /**
     * @var mixed
     */
    protected $widgets;

    /**
     * @var RegisterWidgetEvent
     */
    protected $types;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this('events')->trigger('system.widget', $event = new RegisterWidgetEvent);
        $this->types = $event;
    }

    /**
     * Get a widget instance.
     *
     * @param  string $id
     * @return WidgetInterface
     */
    public function get($id)
    {
        return $this->widgets->find($id);
    }

    /**
     * Returns the rendered widget output, otherwise null.
     *
     * @param  mixed $widget
     * @param  array $options
     * @return string|null
     */
    public function render($widget, $options = array())
    {
        if (!$widget instanceof WidgetInterface) {
            $widget = $this->get($widget);
        }

        if ($widget && $type = $this->types[$widget->getType()]) {
            return $type->render($widget, $options);
        }
    }

    /**
     * @return RegisterWidgetEvent
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return Repository
     */
    public function getWidgetRepository()
    {
        return $this('db.em')->getRepository('Pagekit\Widget\Entity\Widget');
    }
}
