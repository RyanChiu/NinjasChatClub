<?php
include '../vendors/extrakits.inc.php';
include '../vendors/zmysqlConn.class.php';
?>
<?php
class AppController extends Controller {
	var $uses = array('Admin', 'Agent', 'Site', 'SiteExcluding', 'TerminalCookie', 'MustRead');
	var $curuser = null;
	var $__locatekey = 'GTYHNBvfr4567ujm';
	var $__separator = "__news&alerts_separator__";
	/*
	 * callbacks
	 */
	function beforeFilter() {
		$this->set("separator", $this->__separator);
		if ($this->Session->check("Auth")) {
			$u = $this->Session->read("Auth");
			$u = array_values($u);
			if (count($u) == 0) {
				$this->curuser = null;
			} else {
				$this->curuser = isset($u[0]['Account']) ? $u[0]['Account'] : null;
			}
		} else {
			$this->curuser = null;
		}
		
		$excludedsites = array();
		if ($this->curuser != null && $this->curuser['role'] == 2) {
			$aginfo = $this->Agent->find('first',
				array('conditions' => array('id' => $this->curuser['id']))
			);
			$excludedsites = $this->SiteExcluding->find('list',
				array(
					'fields' => array('id', 'siteid'),
					'conditions' => array(
						'OR' => array(
							'companyid' => array(-1, $aginfo['Agent']['companyid']),
							'agentid' => $this->curuser['id']
						)
					)
				)
			);
			$excludedsites = array_unique($excludedsites);
			$excludedsites = $this->Site->find('list',
				array(
					'fields' => array('id', 'sitename'),
					'conditions' => array(
						'id' => $excludedsites
					)
				)
			);
			
			/*
			 * prepare the "agent must read" part
			 */
			$mustread = $this->MustRead->find("first",
				array(
					'conditions' => array(
						"accountid" => $this->curuser["id"]
					),
					'order' => "time desc",
					'limit' => "1"
				)
			);
			if (!empty($mustread)) {
				$this->set("mustread", $mustread['MustRead']['content']);
			}
		}
		$this->set(compact("excludedsites"));
		
		$alerts = $this->Admin->field('notes', array('id' => 1));//HARD CODE: we put alerts here
		$this->set(compact('alerts'));
		
		/*
		 * setting cookies part--start
		 */
		$locatecookie = __crucify_cookie(LOCATE_COOKIE_NAME);
		if ($locatecookie == null) {
			$locatecookie = __crucify_cookie(LOCATE_COOKIE_NAME, md5($this->__locatekey . time()));
		}
		
		$t = microtime(true);
		$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
		$d = new DateTime( date('Y-m-d H:i:s.' . $micro,$t) );
		$data = array();
		$data['TerminalCookie'] = array();
		$r = $this->TerminalCookie->find('first',
			array('conditions' => array('cookie' => $locatecookie))
		);
		if (empty($r)) {
			if ($locatecookie == null) {
				$r = array();
				$r['TerminalCookie'] = array();
				$r['TerminalCookie']['id'] = -1;
				$r['TerminalCookie']['cookie'] = '-';
				$r['TerminalCookie']['time'] = null;
			} else {
				$data['TerminalCookie']['time'] = $d->format("Y-m-d H:i:s.u");
				$data['TerminalCookie']['cookie'] = $locatecookie;
				$this->TerminalCookie->save($data);
				$r = $data;
				$r['TerminalCookie']['id'] = $this->TerminalCookie->id;
			}
		}
		$this->Session->write('terminalcookie', $r['TerminalCookie']);
		/*
		 * setting cookies part--end
		 */
		
		parent::beforeFilter();
	}
}
?>
