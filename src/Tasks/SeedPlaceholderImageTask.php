<?php

namespace Schrattenholz\Order\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\PolyExecution\PolyOutput;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\Folder;
use Schrattenholz\Order\OrderConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Registers a generic "no image available" placeholder in the asset store, so a fresh
 * install doesn't show broken images wherever a Product/Blog page falls back to
 * OrderConfig->ProductImage() and none has been uploaded yet (see DefaultImage() hook
 * chain across Product/BlogExtension/BasicExtension).
 *
 * Deliberately does NOT create the OrderConfig singleton itself -- its other fields
 * (shop emails, footer text, page links, logo, ...) are client-specific content that has
 * to be filled in by hand via the CMS after install. If an OrderConfig record already
 * exists and has no ProductImage set, this task links the placeholder in for convenience;
 * otherwise the image is just made available in the asset store for later manual linking.
 *
 * Idempotent: skips if a file already exists at the target path.
 */
class SeedPlaceholderImageTask extends BuildTask
{
    protected static string $commandName = 'seed-placeholder-image';

    protected string $title = 'Seed placeholder product image';

    protected static string $description = 'Registers a generic "no image available" '
        . 'placeholder in the asset store and links it as OrderConfig->ProductImage if unset.';

    private const TARGET_FOLDER = 'Uploads/system';
    private const TARGET_FILENAME = 'placeholder-product.png';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $targetPath = self::TARGET_FOLDER . '/' . self::TARGET_FILENAME;

        $image = Image::get()->filter('FileFilename', $targetPath)->first();
        if ($image) {
            $output->writeln("'$targetPath' already exists in the asset store (#{$image->ID}) -- skipping upload.");
        } else {
            $sourcePath = BASE_PATH . '/vendor/schrattenholz/order/data/seed/placeholder-product.png';
            if (!file_exists($sourcePath)) {
                $output->writeln("<error>Seed image not found: $sourcePath</error>");
                return Command::FAILURE;
            }

            $folder = Folder::find_or_make(self::TARGET_FOLDER);

            $image = Image::create();
            $image->setFromLocalFile($sourcePath, $targetPath);
            $image->ParentID = $folder->ID;
            $image->Title = 'Platzhalterbild (kein Bild verfügbar)';
            $image->write();
            $image->publishSingle();

            $output->writeln("Uploaded placeholder image as '$targetPath' (#{$image->ID}).");
        }

        $config = OrderConfig::get()->first();
        if (!$config) {
            $output->writeln('No OrderConfig record exists yet -- create the shop config via the '
                . 'CMS ("Shop" section) and link the placeholder image there if desired.');
        } elseif (!$config->ProductImageID) {
            $config->ProductImageID = $image->ID;
            $config->write();
            $output->writeln("Linked placeholder image as OrderConfig (#{$config->ID})'s ProductImage.");
        } else {
            $output->writeln("OrderConfig (#{$config->ID}) already has a ProductImage set -- left as-is.");
        }

        return Command::SUCCESS;
    }
}
