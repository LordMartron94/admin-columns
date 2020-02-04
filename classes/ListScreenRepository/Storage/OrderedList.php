<?php

namespace AC\ListScreenRepository\Storage;

use InvalidArgumentException;
use Iterator;
use OutOfBoundsException;

final class OrderedList implements Iterator {

	/**
	 * @var ListScreenRepository[]
	 */
	private $data;

	public function add( ListScreenRepository $list_screen_repository, $priority = 10 ) {
		$this->data[ $priority ][] = $list_screen_repository;
	}

	public function prepend( ListScreenRepository $list_screen_repository ) {
		array_unshift( $this->data, $list_screen_repository );
	}

	/**
	 * @param ListScreenRepository $list_screen_repository
	 * @param int                  $position
	 */
	public function insert_before( ListScreenRepository $list_screen_repository, $position ) {
		if ( ! is_int( $position ) ) {
			throw new InvalidArgumentException( 'Expected integer for position.' );
		}

		if ( 0 >= $position || $position > $this->count() ) {
			throw new OutOfBoundsException( 'Invalid position found.' );
		}

		array_splice( $this->data, $position, 0, $list_screen_repository );
	}

	public function append( ListScreenRepository $list_screen_repository ) {
		$this->data[] = $list_screen_repository;
	}

	public function count() {
		return count( $this->data );
	}

	public function to_array() {
		return $this->data;
	}

	/**
	 * @return ListScreenRepository
	 */
	public function current() {
		return current( $this->data );
	}

	/**
	 * @inheritDoc
	 */
	public function next() {
		return next( $this->data );
	}

	/**
	 * @inheritDoc
	 */
	public function key() {
		return key( $this->data );
	}

	/**
	 * @inheritDoc
	 */
	public function valid() {
		return null !== $this->key();
	}

	/**
	 * @inheritDoc
	 */
	public function rewind() {
		return reset( $this->data );
	}
}