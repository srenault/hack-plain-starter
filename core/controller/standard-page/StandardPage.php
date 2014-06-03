<?hh // strict

require_once $_SERVER['DOCUMENT_ROOT'].'/core/routes/init.php';

trait StandardPage {
  require extends Controller;

  abstract protected function renderMain(): :xhp;

  protected function getExtraCSS(): Set<string> {
    return Set {};
  }

  protected function getExtraJS(): Set<string> {
    return Set {};
  }

  final protected function getCSS(): Set<string> {
    return (Set {
      '/css/base.css',
    })->addAll($this->getExtraCSS());
  }

  final protected function getJS(): Set<string> {
    return (Set {
    })->addAll($this->getExtraJS());
  }

  final private function renderSearch(?string $ref): :xhp {
      $action = Routes::search($ref);
      return
          <form action={$action} method="get">
            <input type="text" name="q" />
            <input type="submit" value="Search" />
          </form>;
  }

  final private function renderToolbar(): :xhp {
    return
      <div id="toolbar">
          TOOLBAR
      </div>;
  }

  final private function renderFooter(Context $ctx): :xhp {
    if(!$ctx->hasPrivilegedAccess()) {
        return <a href={Routes::signin()}>Sign in to preview changes</a>;
    } else {
        return <a href={Routes::signout()}>Signout</a>;
    }
  }

  final protected function render(Context $ctx): :xhp {
    return
     <div>
       <header>
         {$this->renderToolbar()}
       </header>
         {$this->renderSearch($ctx->getRef())}
       <div class="main">
         {$this->renderMain()}
       </div>
       <footer>
         {$this->renderFooter($ctx)}
       </footer>
     </div>;
  }
}
