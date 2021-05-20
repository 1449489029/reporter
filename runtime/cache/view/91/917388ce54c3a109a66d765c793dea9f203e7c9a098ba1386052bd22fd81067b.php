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

/* Index/upload_file.html */
class __TwigTemplate_6d30b5bbfcbea55f3e55e5c951e57b838d98bbc96c49458553b0246886e8e292 extends \Twig\Template
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
        $this->parent = $this->loadTemplate("base.html", "Index/upload_file.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "Index
";
    }

    // line 8
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 9
        echo "<!--页面头部-->
";
        // line 10
        $this->displayParentBlock("head", $context, $blocks);
        echo "
<style type=\"text/css\">
    .important {
        color: #336699;
    }
</style>
";
    }

    // line 18
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 19
        echo "<!--页面主体-->
<form method=\"post\" enctype=\"multipart/form-data\">
    <input type=\"file\" name=\"file\"/>
    <input type=\"submit\" value=\"提交\">
</form>
";
    }

    public function getTemplateName()
    {
        return "Index/upload_file.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 19,  75 => 18,  64 => 10,  61 => 9,  57 => 8,  52 => 4,  48 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}
Index
{% endblock %}


{% block head %}
<!--页面头部-->
{{ parent() }}
<style type=\"text/css\">
    .important {
        color: #336699;
    }
</style>
{% endblock %}

{% block content %}
<!--页面主体-->
<form method=\"post\" enctype=\"multipart/form-data\">
    <input type=\"file\" name=\"file\"/>
    <input type=\"submit\" value=\"提交\">
</form>
{% endblock %}", "Index/upload_file.html", "D:\\phpstudy_pro\\WWW\\chenbingji\\reporter\\application\\index\\view\\index\\upload_file.html");
    }
}
