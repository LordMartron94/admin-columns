<?php

namespace AC\Controller\Middleware;

use AC\Admin\Preference;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use Exception;

class ListScreenAdmin implements Middleware
{

    private $storage;

    private $list_key;

    private $preference;

    private $list_screen_factory;

    public function __construct(
        Storage $storage,
        ListKey $list_key,
        Preference\ListScreen $preference,
        ListScreenFactory $list_screen_factory
    ) {
        $this->storage = $storage;
        $this->list_key = $list_key;
        $this->preference = $preference;
        $this->list_screen_factory = $list_screen_factory;
    }

    private function get_requested_list_screen(Request $request): ?ListScreen
    {
        try {
            $id = new ListScreenId((string)$request->get('layout_id'));
        } catch (Exception $e) {
            return null;
        }

        return $this->get_list_screen_by_id($id);
    }

    private function get_list_screen_by_id(ListScreenId $id): ?ListScreen
    {
        $list_screen = $this->storage->find($id);

        return $list_screen && $this->list_key->equals($list_screen->get_key())
            ? $list_screen
            : null;
    }

    private function get_first_listscreen(): ?ListScreen
    {
        $list_screens = $this->storage->find_all_by_key((string)$this->list_key);

        return $list_screens->count() > 0
            ? $list_screens->current()
            : null;
    }

    private function get_last_visited_listscreen(): ?ListScreen
    {
        try {
            $list_id = new ListScreenId((string)$this->preference->get_list_id((string)$this->list_key));
        } catch (Exception $e) {
            return null;
        }

        return $this->get_list_screen_by_id($list_id);
    }

    private function get_list_screen(Request $request): ?ListScreen
    {
        $list_screen = $this->get_requested_list_screen($request);

        if ( ! $list_screen) {
            $list_screen = $this->get_last_visited_listscreen();
        }

        if ( ! $list_screen) {
            $list_screen = $this->get_first_listscreen();
        }

        if ( ! $list_screen && $this->list_screen_factory->can_create($this->list_key)) {
            $list_screen = $this->list_screen_factory->create(
                $this->list_key,
                [
                    'list_id' => (string)ListScreenId::generate(),
                ]
            );
        }

        return $list_screen;
    }

    public function handle(Request $request): void
    {
        $request->get_parameters()->merge([
            'list_screen' => $this->get_list_screen($request),
        ]);
    }

}