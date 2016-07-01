<?php

class Choose_Controller_Router_Admin extends Choose_Controller_Router
{
    /**
     * ログイン状態によりコントローラ名を生成
     *
     * @param type $request
     * @param type $params
     */
    protected function _setRequestParams($request, $params)
    {
        foreach ($params as $param => $value) {

            $request->setParam($param, $value);

            if ($param === $request->getModuleKey()) {
                $request->setModuleName($value);
            }
            if ($param === $request->getControllerKey()) {

                if (!isset($params['roll'])) {
                    $params['roll'] = 'index';
                }

                $controller = preg_replace('/_$/', '',
                                ucfirst($params['roll']) . '_' . ucfirst($value));
                $request->setControllerName($controller);
            }
            if ($param === $request->getActionKey()) {
                $request->setActionName($value);
            }
        }
    }
}
