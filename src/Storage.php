<?php

namespace Passwogen;

use Symfony\Component\Filesystem\Filesystem;

class Storage
{
    /**
     * @var string
     */
    protected $passwd;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $passwd
     * @param string $path
     */
    public function __construct($passwd, $path)
    {
        $this->passwd = $passwd;
        $this->path = $path;
    }

    /**
     * @param string $key
     * @return null|array
     */
    public function get($key)
    {
        $result = null;
        foreach ($this->getItems() as $item) {
            if ($item['key'] === $key) {
                $result = $item;
                break;
            }
        }

        return $result;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $updated = false;
        $items = $this->getItems();
        $curr = (new \DateTime('now'))->format('Y-m-d H:i:s');

        foreach ($items as $idx => $item) {
            if ($item['key'] === $key) {
                $updated = true;
                $items[$idx]['password'] = $value;
                $items[$idx]['time'] = $curr;
                break;
            }
        }

        if (!$updated) {
            $items[] = [
                'key' => $key,
                'password' => $value,
                'time' => $curr,
            ];
        }

        $this->save($items);
    }

    public function find($key)
    {
        $result = [];
        foreach ($this->getItems() as $item) {
            if (@preg_match("/{$key}/i", $item['key'])) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getItems()
    {
        $fs = new Filesystem();
        if (!$fs->exists($this->path)) {
            return [];
        }

        $content = file_get_contents($this->path);
        if (!$content) {
            return [];
        }

        $data = json_decode($this->decode($content), true);
        if (!$data) {
            throw new \Exception('Invalid master password');
        }

        return $data;
    }

    /**
     * @param array $data
     */
    protected function save(array $data)
    {
        $content = $this->encode(json_encode($data) . PHP_EOL);

        $fs = new Filesystem();
        if (!$fs->exists($this->path)) {
            $fs->touch($this->path);
        }

        $fs->dumpFile($this->path, chunk_split($content, 64));
    }

    /**
     * @param string $str
     * @return string
     */
    protected function encode($str)
    {
        return openssl_encrypt(gzencode($str), 'bf', $this->passwd, 0, $this->getVector());
    }

    /**
     * @param string $raw
     * @return string
     */
    protected function decode($raw)
    {
        return @gzdecode(openssl_decrypt($raw, 'bf', $this->passwd, 0, $this->getVector()));
    }

    /**
     * @return string
     */
    protected function getVector()
    {
        return substr(sha1($this->passwd), 0, 8);
    }
}
