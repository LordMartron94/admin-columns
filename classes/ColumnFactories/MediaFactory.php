<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\Media;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class MediaFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        if ( ! $table_screen instanceof AC\TableScreen\Media) {
            return null;
        }

        $factories[] = $this->container->get(Media\AlbumFactory::class);
        $factories[] = $this->container->get(Media\AternateTextFactory::class);
        $factories[] = $this->container->get(Media\ArtistFactory::class);
        $factories[] = $this->container->get(Media\AudioPlayerFactory::class);
        $factories[] = $this->container->get(Media\AvailableSizesFactory::class);
        $factories[] = $this->container->get(Media\CaptionFactory::class);
        $factories[] = $this->container->get(Media\DescriptionFactory::class);
        $factories[] = $this->container->get(Media\DimensionsFactory::class);
        $factories[] = $this->container->get(Media\DownloadFactory::class);
        $factories[] = $this->container->get(Media\ExifDataFactory::class);
        //        $factories[] = $this->container->make(Media\ExifData::class, [
        //            'exif_data_factory' => new AC\Settings\Column\ExifDataFactory(),
        //        ]);

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;
    }

}