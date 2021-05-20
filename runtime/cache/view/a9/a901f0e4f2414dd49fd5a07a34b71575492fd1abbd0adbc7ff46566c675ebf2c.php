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

/* Items/Add.html */
class __TwigTemplate_e09231807011200fb80f9dbabc37fad2267724dd2daeefaf1c3f3d9242641481 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'head' => [$this, 'block_head'],
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
        $this->parent = $this->loadTemplate("base.html", "Items/Add.html", 1);
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
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 8
        echo "<script type=\"text/javascript\" src=\"/admin/js/items/AddItems.js\"></script>
<script type=\"text/javascript\">

    \$(function () {
        var AddItemsObject = window.AddItemsObject = new AddItems();
        // 初始化
        AddItemsObject.init();
        // 绑定事件
        AddItemsObject.bind_events();
    })

</script>
<style>
    .tips {
        display: none;
        width: 100px;
        padding: 10px;
        background: rgba(0, 0, 0, 0.5);
        color: #ffffff;
        text-align: center;
        position: fixed;
        top: 50%;
        left: 50%;
        z-index: 10;
    }
</style>
";
    }

    // line 36
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 37
        echo "
<div id=\"tips\"></div>

<!-- Input group addons -->
<div class=\"panel panel-flat\">
    <div class=\"panel-heading\">
        <h5 class=\"panel-title\">添加物品</h5>
        <div class=\"heading-elements\">
            <ul class=\"icons-list\">
            </ul>
        </div>
    </div>

    <div class=\"panel-body\">
        <p class=\"content-group-lg\">
            选择对应的服务器给指定角色添加对应物品
        </p>

        <form class=\"form-horizontal\" action=\"#\">
            <fieldset class=\"content-group\">

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">服务器</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select2-container select\" name=\"server_options\">
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">角色ID</label>
                    <div class=\"col-lg-10\">
                        <div class=\"input-group\">
                            <span class=\"input-group-addon\"><i class=\"icon-user\"></i></span>
                            <input type=\"number\" class=\"form-control\" placeholder=\"角色ID\" name=\"role_id\">
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">背包</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select2-container select\" name=\"backpack_options\">
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">物品</label>
                    <div class=\"col-lg-10\">
                        <select class=\"select-search\" name=\"static_item_options\">
                            <option value=\"0\">请选择物品</option>
                        </select>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">添加的数量</label>
                    <div class=\"col-lg-10\">
                        <div class=\"input-group\">
                            <input type=\"number\" class=\"form-control\" name=\"quantity\" placeholder=\"添加的数量\">
                            <span class=\"input-group-addon\">个</span>
                        </div>
                    </div>
                </div>

                <div class=\"text-right\">
                    <button type=\"button\" class=\"btn btn-primary\" id=\"add_item\">添加<i class=\"icon-arrow-right14 position-right\"></i></button>
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
        return "Items/Add.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  95 => 37,  91 => 36,  61 => 8,  57 => 7,  52 => 4,  48 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}
添加物品
{% endblock %}

{% block head %}
<script type=\"text/javascript\" src=\"/admin/js/items/AddItems.js\"></script>
<script type=\"text/javascript\">

    \$(function () {
        var AddItemsObject = window.AddItemsObject = new AddItems();
        // 初始化
        AddItemsObject.init();
        // 绑定事件
        AddItemsObject.bind_events();
    })

</script>
<style>
    .tips {
        display: none;
        width: 100px;
        padding: 10px;
        background: rgba(0, 0, 0, 0.5);
        color: #ffffff;
        text-align: center;
        position: fixed;
        top: 50%;
        left: 50%;
        z-index: 10;
    }
</style>
{% endblock %}

{% block content %}

<div id=\"tips\"></div>

<!-- Input group addons -->
<div class=\"panel panel-flat\">
    <div class=\"panel-heading\">
        <h5 class=\"panel-title\">添加物品</h5>
        <div class=\"heading-elements\">
            <ul class=\"icons-list\">
            </ul>
        </div>
    </div>

    <div class=\"panel-body\">
        <p class=\"content-group-lg\">
            选择对应的服务器给指定角色添加对应物品
        </p>

        <form class=\"form-horizontal\" action=\"#\">
            <fieldset class=\"content-group\">

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">服务器</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select2-container select\" name=\"server_options\">
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">角色ID</label>
                    <div class=\"col-lg-10\">
                        <div class=\"input-group\">
                            <span class=\"input-group-addon\"><i class=\"icon-user\"></i></span>
                            <input type=\"number\" class=\"form-control\" placeholder=\"角色ID\" name=\"role_id\">
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">背包</label>
                    <div class=\"col-lg-10\">
                        <div class=\"form-group\">
                            <select class=\"select2-container select\" name=\"backpack_options\">
                            </select>
                        </div>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">物品</label>
                    <div class=\"col-lg-10\">
                        <select class=\"select-search\" name=\"static_item_options\">
                            <option value=\"0\">请选择物品</option>
                        </select>
                    </div>
                </div>

                <div class=\"form-group\">
                    <label class=\"control-label col-lg-2\">添加的数量</label>
                    <div class=\"col-lg-10\">
                        <div class=\"input-group\">
                            <input type=\"number\" class=\"form-control\" name=\"quantity\" placeholder=\"添加的数量\">
                            <span class=\"input-group-addon\">个</span>
                        </div>
                    </div>
                </div>

                <div class=\"text-right\">
                    <button type=\"button\" class=\"btn btn-primary\" id=\"add_item\">添加<i class=\"icon-arrow-right14 position-right\"></i></button>
                </div>


            </fieldset>
        </form>
    </div>
</div>
<!-- /input group addons -->

{% endblock %}", "Items/Add.html", "D:\\phpstudy_pro\\WWW\\chenbingji\\reporter\\application\\admin\\view\\items\\Add.html");
    }
}
