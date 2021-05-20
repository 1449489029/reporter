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

/* Index/show_view.html */
class __TwigTemplate_83de362a9c73a6214788a9909d6ff17189aee33ddeb1e1d648f5cb69598366bc extends \Twig\Template
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
        $this->parent = $this->loadTemplate("base.html", "Index/show_view.html", 1);
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
<h1>Index</h1>
<p class=\"important\">
    Reporter
    ";
        // line 23
        echo twig_escape_filter($this->env, ($context["get"] ?? null), "html", null, true);
        echo "
</p>
";
    }

    public function getTemplateName()
    {
        return "Index/show_view.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 23,  79 => 19,  75 => 18,  64 => 10,  61 => 9,  57 => 8,  52 => 4,  48 => 3,  37 => 1,);
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
<h1>Index</h1>
<p class=\"important\">
    Reporter
    {{ get }}
</p>
{% endblock %}", "Index/show_view.html", "D:\\phpstudy_pro\\WWW\\chenbingji\\reporter\\application\\index\\view\\index\\show_view.html");
    }
}
