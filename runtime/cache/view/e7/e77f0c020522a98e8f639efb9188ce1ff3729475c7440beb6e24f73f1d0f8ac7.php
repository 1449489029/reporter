<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* Items/AddItem.html */
class __TwigTemplate_eb4f5d59e855647506cbf34c07668d29cb78f3a1907fe5048707d965af89493b extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.html";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base.html", "Items/AddItem.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "添加物品
";
    }

    // line 7
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 8
        echo "<!-- Input group addons -->
<div class=\"panel panel-flat\">
    <div class=\"panel-heading\">
        <h5 class=\"panel-title\">添加物品</h5>
        <div class=\"heading-elements\">
            <ul class=\"icons-list\">
                <!--                <li><a data-action=\"collapse\"></a></li>-->
                <!--                <li><a data-action=\"reload\"></a></li>-->
                <!--                <li><a data-action=\"close\"></a></li>-->
            </ul>
        </div>
    </div>

    <div class=\"panel-body\">
        <p class=\"content-group-lg\">
            选择对应的服务器给指定角色添加对应物品
        </p>

        <form class=\"form-horizontal\" action=\"#\">
            <fieldset class=\"content-group\">
                <legend class=\"text-bold\">Text addon</legend>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">角色ID</label>
                    <div class=\"col-lg-10\">
                        <div class=\"input-group\">
                            <span class=\"input-group-addon\"><i class=\"icon-user\"></i></span>
                            <input type=\"text\" class=\"form-control\" placeholder=\"角色ID\">
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">服务器</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select-search\">
                                <option value=\"1\">本电脑</option>
                                <option value=\"2\">本地服务器</option>
                                <option value=\"3\">测试服务器</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">背包</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select-search\">
                                <option value=\"0\">天道背包</option>
                                <option value=\"1\">我的星球</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">物品</label>
                    <div class=\"col-lg-10\">
                        <select class=\"select-search\">
                            <optgroup label=\"Mountain Time Zone\">
                                <option value=\"0\">天道背包</option>
                                <option value=\"1\">我的星球</option>
                            </optgroup>
                        </select>
                    </div>
                </div>


            </fieldset>
        </form>
    </div>
</div>
<!-- /input group addons -->

";
    }

    public function getTemplateName()
    {
        return "Items/AddItem.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 8,  56 => 7,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}
添加物品
{% endblock %}

{% block content %}
<!-- Input group addons -->
<div class=\"panel panel-flat\">
    <div class=\"panel-heading\">
        <h5 class=\"panel-title\">添加物品</h5>
        <div class=\"heading-elements\">
            <ul class=\"icons-list\">
                <!--                <li><a data-action=\"collapse\"></a></li>-->
                <!--                <li><a data-action=\"reload\"></a></li>-->
                <!--                <li><a data-action=\"close\"></a></li>-->
            </ul>
        </div>
    </div>

    <div class=\"panel-body\">
        <p class=\"content-group-lg\">
            选择对应的服务器给指定角色添加对应物品
        </p>

        <form class=\"form-horizontal\" action=\"#\">
            <fieldset class=\"content-group\">
                <legend class=\"text-bold\">Text addon</legend>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">角色ID</label>
                    <div class=\"col-lg-10\">
                        <div class=\"input-group\">
                            <span class=\"input-group-addon\"><i class=\"icon-user\"></i></span>
                            <input type=\"text\" class=\"form-control\" placeholder=\"角色ID\">
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">服务器</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select-search\">
                                <option value=\"1\">本电脑</option>
                                <option value=\"2\">本地服务器</option>
                                <option value=\"3\">测试服务器</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">背包</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select-search\">
                                <option value=\"0\">天道背包</option>
                                <option value=\"1\">我的星球</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">物品</label>
                    <div class=\"col-lg-10\">
                        <select class=\"select-search\">
                            <optgroup label=\"Mountain Time Zone\">
                                <option value=\"0\">天道背包</option>
                                <option value=\"1\">我的星球</option>
                            </optgroup>
                        </select>
                    </div>
                </div>


            </fieldset>
        </form>
    </div>
</div>
<!-- /input group addons -->

{% endblock %}", "Items/AddItem.html", "D:\\phpstudy_pro\\WWW\\chenbingji\\reporter\\application\\admin\\view\\items\\AddItem.html");
    }
}
