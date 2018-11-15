<?php

/* ticket.html */
class __TwigTemplate_9be236648a8ce3bcc6ffd17a3c716c192d25ad18fd52d3362fd5fcbec2a0f35a extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
  <head>
    
  </head>
  <body>
    
    <table>
      <thead>
        
      </thead>
      <tbody>
        <tr>
          <td>Title</td>
          <td>";
        // line 15
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</td>
        </tr>
      </tbody>
    </table>
  </body>
</html>";
    }

    public function getTemplateName()
    {
        return "ticket.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 15,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "ticket.html", "C:\\xampp\\htdocs\\oclcAPIs\\circulation\\pulllist\\ticket.html");
    }
}
