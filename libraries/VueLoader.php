<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VueLoader
{
  /**
   * [groups array from @vue config.]
   *
   * @var array
   */
  private $groups;

  /**
   * [__construct Class Constructor]
   *
   * @date  2020-02-09
   *
   * @param array|null $params Initialization array. config values in
   *                           app/vue.php will be passed here if loaded with
   *                           $this->load->package('francis94c/vue-ci');
   */
  function __construct(?array $params=null)
  {
    get_instance()->load->helper('inflector');

    if ($params) $this->groups = $params['groups'] ?? [];
  }

  /**
   * [loadGroup Load Vue Component Group.]
   *
   * @date  2020-02-09
   *
   * @param string $groupName Group Name.
   */
  public function loadGroup(string $groupName):void
  {
    $prefix = $this->groups[$groupName]['components_view_prefix'] ?? '';
    foreach ($this->groups[$groupName]['components'] ?? [] as $component) {
      $this->loadComponent($prefix.$component);
    }
    foreach ($this->groups[$groupName]['scripts'] ?? [] as $script) {
      $this->loadScript($script);
    }
  }

  /**
   * [loadScript Load a Code Ignoter View file (PHP) as JS, stripping off any
   * opening and closing <script> tags contained wihin and minifying the
   * resulting script.]
   *
   * @date  2020-02-09
   *
   * @param string $viewPath Path to View, Pass what you would pass to
   * $this->load->view if you were in a Controller.
   */
  public function loadScript(string $viewPath):void
  {
    $script = get_instance()->load->view($viewPath, null, true);
    $script = preg_replace('/\/\*[\s\S]*?\*\/|([^\\:]|^)\/\/.*$/m', '$1', $script);
    echo preg_replace('/(<script>|<script type="text\/javascript">|<\/script>|\r|\n|  )/', '', $script);
  }

  /**
   * [loadComponent Load a Code Ignoter View file (PHP) as Vue Component (.vue file),
   * stripping off any opening and closing <script> tags contained wihin and
   * minifying the resulting script. It additionally processes the contents of
   * the <template> block and passes it as a property to the component script
   * template field. The contents of the <script> block are equally assigned to
   * a variable matching the given $viewPath in camel casing with the words 'vue'
   * and 'components' ommited from it.]
   *
   * @date  2020-02-09
   *
   * @param string $viewPath Path to View, Pass what you would pass to
   * $this->load->view if you were in a Controller.
   */
  public function loadComponent($viewPath):void
  {
    $component = get_instance()->load->view($viewPath, null, true);

    preg_match_all('/<template>.+<\/template>/s', $component, $matches);
    $template = $this->prepare_template($matches[0][0]);

    preg_match_all('/<script>.+<\/script>/s', $component, $matches);
    $script = $matches[0][0];

    $script = preg_replace('/template:(.)+,/', '', $script);

    if (preg_match('/{/', $script, $matches, PREG_OFFSET_CAPTURE)) {
      $script = substr_replace($script, "template: '$template', ", $matches[0][1] + 1, 0);
    }

    $script = preg_replace('/\/\*[\s\S]*?\*\/|([^\\:]|^)\/\/.*$/m', '$1', $script);

    $script = preg_replace('/(<script>|<script type="text\/javascript">|<\/script>|\r|\n|  )/', '', $script);

    $viewPath = str_replace('vue/', '', $viewPath);
    $viewPath = str_replace('components/', '', $viewPath);

    $script = 'let ' . camelize(preg_replace('/(-|\/|\\\)/', '_', $viewPath)) . ' = ' . $script;

    echo $script;
  }

  /**
   * [prepare_template Minifies the contents of a <template> block, escapes
   * single quote characters and strips of the <template> tags.]
   *
   * @date   2020-02-09
   *
   * @param  string $template String (HTML) enclosed in <template> tags.
   * @return string           Processed/Prepared template string.
   */
  private function prepare_template(string $template):string
  {
    $template = preg_replace('/(<(|\/)template>)/', '', $template);
    $template = preg_replace('/(\r|\n|\r\n)/', '', $template);
    return trim(str_replace("'", "\\'", $template));
  }
}
