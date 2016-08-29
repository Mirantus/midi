<?php
    /**
     * File class
     *
     * @author Mikhail Miropolskiy <the-ms@ya.ru>
     * @package Core
     * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
     */
    namespace lib;

    class File {
        /**
         * @param string $source Path to dir
         * @return array Files of dir
         * @throws \Exception
         */
        public static function dir ($source) {
            if (!file_exists($source)) {
                throw new \Exception('Невозможно найти директорию ' . $source);
            }

            if (!is_dir($source)) {
                throw new \Exception($source . ' не является директорией');
            }

            $files = scandir($source);
            if (!$files) {
                throw new \Exception('Невозможно прочитать директорию ' . $source);
            }

            return array_diff($files, ['..', '.']);
        }

        /**
         * @param string $source
         * @param string $destination
         * @throws \Exception
         */
        public static function copy ($source, $destination) {
            if (is_dir($source)) {
                static::copyDirectory($source, $destination);
            } else {
                static::copyFile($source, $destination);
            }
        }

        /**
         * @param string $source
         * @param string $destination
         * @throws \Exception
         */
        public static function move ($source, $destination) {
            if (!file_exists($source)) {
                throw new \Exception('Невозможно найти ' . $source);
            }

            static::mkdir(dirname($destination));

            if (!rename($source, $destination)) {
                throw new \Exception('Невозможно переместить ' . $source . ' в ' . $destination);
            }
        }

        /**
         * @param string $file
         * @param string $destination
         * @throws \Exception
         */
        public static function moveUploadedFile ($file, $destination) {
            static::mkdir(dirname($destination));
            if (!move_uploaded_file($file, $destination)) {
                throw new \Exception('Невозможно сохранить файл ' . $destination);
            };
        }

        /**
         * @param string $destination
         * @throws \Exception
         */
        public static function mkdir ($destination) {
            if (!mkdir($destination, 0666, true)) {
                throw new \Exception('Невозможно создать директорию ' . $destination);
            };
        }

        /**
         * @param string $source
         * @param string $destination
         * @throws \Exception
         */
        private static function copyDirectory ($source, $destination) {
            if (!file_exists($source)) {
                throw new \Exception('Невозможно найти директорию ' . $source);
            }

            static::mkdir($destination);

            foreach (
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::SELF_FIRST) as $item
            ) {
                if ($item->isDir()) {
                    static::mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                } else {
                    static::copyFile($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                }
            }


            if (!copy($source, $destination)) {
                throw new \Exception('Невозможно скопировать файл ' . $source . ' в ' . $destination);
            };
        }

        /**
         * @param string $source
         * @param string $destination
         * @throws \Exception
         */
        private static function copyFile ($source, $destination) {
            if (!file_exists($source)) {
                throw new \Exception('Невозможно найти файл ' . $source);
            }

            static::mkdir(dirname($destination));

            if (!copy($source, $destination)) {
                throw new \Exception('Невозможно скопировать файл ' . $source . ' в ' . $destination);
            };
        }
    }