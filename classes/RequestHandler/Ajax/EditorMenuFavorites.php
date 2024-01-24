<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Storage;
use AC\Type\ListKey;

class EditorMenuFavorites implements RequestAjaxHandler
{

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $preference = new Storage\Model\EditorFavorites();

        'favorite' === $request->get('status')
            ? $preference->set_as_favorite(new ListKey($request->get('list_key')))
            : $preference->remove_as_favorite(new ListKey($request->get('list_key')));

        $response->success();
    }

}