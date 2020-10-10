<?php namespace Seblhaire\BootstrapPaginator;

/**
 * Description of BootstrapPaginatorService
 *
 * @author seb
 */
class BootstrapPaginatorProvider{

    protected $currentitem;
    protected $currentroute;
    protected $options;

    public function __construct($currentitem, $currentroute, $options = []){
        $this->currentitem = $currentitem;
        $this->currentroute = $currentroute;
        if ($this->checkOptions($options)){
		        $this->options = array_replace(
		            config('bootstrappaginator'),
		            $options
		        );
		    }else{
		        throw new \Exception('wrong option');
		    }
    }

    private function checkOptions($options){
        if (is_array($options)){
            $checkoptions = array(
              'class' => 'is_string',
              'itemclass' => 'is_string',
              'linkclass' => 'is_string',
              'activelink' => 'is_string',
              'disabledlink' => 'is_string',
              'withoutdots' => 'is_boolean',
              'pageparam' => 'is_string',
              'nbpages' => 'is_numeric',
              'initialparam' => 'is_string',
              'valueforall' => 'is_numeric',
              'type' => 'is_string',
              'nbpages' => 'is_numeric',
              'max_pages_without_dots' => 'is_numeric',
  	       'items_before_after_current' => 'is_numeric',
              'params' => 'is_array',
              'getparams' => 'is_array',
              'srcurrent' => 'is_string',
              'previousbuttoncontent' => 'is_string',
              'nextbuttoncontent' => 'is_string',
            );
            $aKeys = array_keys($checkoptions);
            foreach($options as $sKey => $sValue){
                if (!in_array($sKey, $aKeys) || !$checkoptions[$sKey]($sValue)){
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    private function boucle($start, $max){
        $str = '';
        for ($i = $start; $i <= $max; $i++){
            $str .= "<li";
            if ($i == $this->currentitem){
                $str .= " class=\"" . $this->options['itemclass'] . $this->options["activelink"] . "\"";
            }else{
                $str .= " class=\"" . $this->options['itemclass'] . "\"";
            }
            $str .= "><a class=\"" . $this->options['linkclass'] . "\" href=\"" .
              route($this->currentroute, array_merge($this->options['params'], [$this->options['pageparam'] => $i])) .
                    $this->buildGet() .
                    "\">" . $i . ($i == $this->currentitem ? $this->options['srcurrent'] :'') . "</a></li>";
        }
        return $str;
    }
    private function dots(){
        return "<li class=\"" . $this->options['itemclass'] . $this->options["disabledlink"] ."\"><a class=\"" .
          $this->options['linkclass'] . "\" href=\"#\">...</a></li>";
    }

    private function buildGet(){
        $str = '';
        if (count($this->options['getparams'])> 0){
            foreach ($this->options['getparams'] as $key => $val){
                if (strlen($str) > 0){
                    $str .= "&";
                }else{
                    $str .= "?";
                }
                $str .= $key . '=' . $val;
            }
        }
        return $str;
    }

    private function numeric(){
        // container
        $str = "<nav aria-label=\"...\"><ul class=\"" . $this->options['class'] . "\">";
        // button previous
        if ($this->currentitem == 1){
            $str .= "<li class=\"" . $this->options['itemclass'] . $this->options["disabledlink"] . "\"";
        }else{
          $str .= "<li class=\"" . $this->options['itemclass'] . "\"";
        }
        $str .= "><a class=\"" . $this->options['linkclass'] . "\" title=\"" .  __('pagination.previous') ."\" href=\"" .
          route($this->currentroute, array_merge($this->options['params'], [$this->options['pageparam'] => $this->currentitem - 1])) .
                $this->buildGet() .
                "\" aria-label=\"" .  __('pagination.previous') ."\">" . $this->options["previousbuttoncontent"] . "</a></li>";
        //end btn previous
        if ($this->options['nbpages'] <= $this->options['max_pages_without_dots'] || $this->options['withoutdots']){
        // all pages displayed
            $str .= $this->boucle(1, $this->options['nbpages']);
        }else{
            // page 1
            if ($this->currentitem == 1){
                $str .= "<li class=\"" . $this->options['itemclass'] . $this->options["activelink"] . "\"";
            }else{
                $str .= "<li class=\"" . $this->options['itemclass'] . "\"";
            }
            $str .= "><a class=\"" . $this->options['linkclass'] . "\" href=\"" .
              route($this->currentroute, array_merge($this->options['params'], [$this->options['pageparam'] => 1])) .
                    $this->buildGet() .
                    "\">1" . ($this->currentitem == 1 ? $this->options['srcurrent'] :'') . "</a></li>";
            // next pages
            if ($this->currentitem <= (1+ (2* $this->options['items_before_after_current']))){
                $str .= $this->boucle(2, (1+ (2* $this->options['items_before_after_current']))) . $this->dots();

            }else if ($this->currentitem >= ($this->options['nbpages'] - (1+ (2* $this->options['items_before_after_current'])))){
                //pagination: l<=9; p<=5; p>= l-4; default...
                $str .=  $this->dots() . $this->boucle(($this->options['nbpages'] - (1+ (2* $this->options['items_before_after_current']))), ($this->options['nbpages'] -1)) ;
            }else{
                $str .= $this->dots() . $this->boucle(($this->currentitem - $this->options['items_before_after_current']), ($this->currentitem + $this->options['items_before_after_current'])) . $this->dots();
            }
            //Last page
            $str .= "<li";
            if ($this->currentitem == $this->options['nbpages']){
                $str .= " class=\"" . $this->options['itemclass'] . $this->options["activelink"] . "\"";
            }else{
                $str .= " class=\"" . $this->options['itemclass'] . "\"";
            }
            $str .= "><a class=\"" . $this->options['linkclass'] . "\" href=\"" .
              route($this->currentroute, array_merge($this->options['params'], [$this->options['pageparam'] => $this->options['nbpages']])) .
                    $this->buildGet() .
                    "\">" . $this->options['nbpages'] . ($this->currentitem == $this->options['nbpages'] ? $this->options['srcurrent'] :'') . "</a></li>";
        }
        //latst button
        $str .= "<li";
        if ($this->currentitem == $this->options['nbpages']){
            $str .= " class=\"" . $this->options['itemclass'] . $this->options["disabledlink"] . "\"";
        }else{
          $str .= " class=\"" . $this->options['itemclass'] . "\"";
        }
        $str .= "><a class=\"" . $this->options['linkclass'] . "\" title=\"" . __('pagination.next') . "\" href=\"" .
          route($this->currentroute, array_merge($this->options['params'], [$this->options['pageparam'] => $this->currentitem + 1])) .
                $this->buildGet() .
                "\" aria-label=\"" . __('pagination.next') . "\">" . $this->options["nextbuttoncontent"] . "</a></li>";
        $str .= "</ul></nav>";
        return $str;
    }

    private function alpha(){
        $str = "<nav aria-label=\"...\"><ul class=\"" . $this->options['class'] . "\"><li ";
        if ($this->currentitem == $this->options['valueforall']){
            $str .= "class=\"" . $this->options['itemclass'] . $this->options["activelink"] . "\"";
        }else{
           $str .= "class=\"" . $this->options['itemclass'] . "\"";
        }
        $str .= "><a class=\"" . $this->options['linkclass'] . "\" href=\"" .
          route($this->currentroute, array_merge($this->options['params'], [
            $this->options['initialparam'] => $this->options['valueforall'],
            $this->options['pageparam'] => 1])) .
                "\" >" .  __('pagination.all') . "</a></li>";
        for ($i = 65; $i < 91; $i++){
            $str .= "<li ";
            if (ord($this->currentitem) == $i){
                $str .= "class=\"" . $this->options['itemclass'] . $this->options["activelink"] . "\"";
            }else{
               $str .= "class=\"" . $this->options['itemclass'] . "\"";
            }
            $str .= "><a class=\"" . $this->options['linkclass'] . "\" href=\"" .
              route($this->currentroute, array_merge($this->options['params'], [
                $this->options['initialparam'] => chr($i), $this->options['pageparam'] => 1])) .
                "\" >" . chr($i) . "</a></li>";
        }
        $str .= "</ul></nav>";
        return $str;
    }

    public function render(){
      if ($this->options['type'] == 'numeric'){
        return $this->numeric();
      }else{
        return $this->alpha();
      }
    }

    public function __toString(){
      return $this->render();
    }
}

