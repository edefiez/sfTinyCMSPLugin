<?php
/**
 * sfAlohaContentActions actions.
 */
class sfTinyCmsContentActions extends sfActions
{
	public function preExecute()
	{
		$this->forward404Unless(sfTinyCMS::checkAccess());
	}

	public function executeSave(sfWebRequest $request)
	{
		$name   = $request->getParameter('name');
		$text   = $request->getParameter('text');

		if (!$name || !$text)	$this->forward404();

		$content = TinyCmsContentTable::getInstance()->findOneBy('name', $name);
		if($content)	$content->delete();

		$content = new TinyCmsContent();
		$content->set('name', $name);
		$content->set('text', $text);
		$content->save(); 

		return $this->renderText(json_encode(true));

	}
}