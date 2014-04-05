<?php
//<上帝未满18岁> | <qq1054000800@gmail.com> | <www.phpjcw.com>
safe();
/*
 *文件上传类
 * $_FILE//上传文件
 * move_upload_file()//移动上传文件
 * */
class UploadFile {
	public $postName = ''; //post表单名称
	public $maxSize = -1; // 上传文件的最大值,-1无限制
	public $saveDir = ''; //上传文件保存路径
	public $childDir = ''; //保存路径下的子目录
	public $allowExts = false; //允许的后缀
	private $error = ''; // 错误信息

	public function __construct($postName) {
		$this->childDir = date('Y-m'); //设置默认子目录
		$this->postName = $postName;
	}

	//上传一个文件
	private function save($fileInfo) {
		$filename = $fileInfo['saveName'];
		// 如果是图像文件 检测文件格式
		if (in_array(strtolower($fileInfo['extension']), array(
			'gif',
			'jpg',
			'jpeg',
			'bmp',
			'png'
		))
		) {
			$info = getimagesize($fileInfo['tmp_name']);
			if (false === $info) {
				$this->error = '非法图像文件';
				return false;
			}
		}
		if (!move_uploaded_file($fileInfo['tmp_name'], $filename)) {
			$this->error = '文件上传保存错误！';
			return false;
		}
		//保存缩略图
		/*
		if ($this->thumb
		    && in_array(strtolower($file['extension']), array(
				'gif',
				'jpg',
				'jpeg',
				'bmp',
				'png'
			))
		) {
			$image = getimagesize($filename);
			if (false !== $image) {
				//是图像文件生成缩略图
				$thumbWidth  = explode(',', $this->thumbMaxWidth);
				$thumbHeight = explode(',', $this->thumbMaxHeight);
				$thumbPrefix = explode(',', $this->thumbPrefix);
				$thumbSuffix = explode(',', $this->thumbSuffix);
				$thumbFile   = explode(',', $this->thumbFile);
				$thumbPath   = $this->thumbPath ? $this->thumbPath : dirname($filename).'/';
				$thumbExt    = $this->thumbExt ? $this->thumbExt : $file['extension']; //自定义缩略图扩展名
				// 生成图像缩略图
				import($this->imageClassPath);
				for ($i = 0, $len = count($thumbWidth); $i < $len; $i++) {
					if (!empty($thumbFile[$i])) {
						$thumbname = $thumbFile[$i];
					} else {
						$prefix    = isset($thumbPrefix[$i]) ? $thumbPrefix[$i] : $thumbPrefix[0];
						$suffix    = isset($thumbSuffix[$i]) ? $thumbSuffix[$i] : $thumbSuffix[0];
						$thumbname = $prefix.basename($filename, '.'.$file['extension']).$suffix;
					}
					if (1 == $this->thumbType) {
						Image::thumb2($filename,
							$thumbPath.$thumbname.'.'.$thumbExt, '', $thumbWidth[$i], $thumbHeight[$i], true);
					} else {
						Image::thumb($filename,
							$thumbPath.$thumbname.'.'.$thumbExt, '', $thumbWidth[$i], $thumbHeight[$i], true);
					}

				}
				if ($this->thumbRemoveOrigin) {
					// 生成缩略图之后删除原图
					unlink($filename);
				}
			}
		}
		if ($this->zipImags) {
			// TODO 对图片压缩包在线解压
		}
		*/
		return true;
	}

	//上传一个文件
	public function upload() {
		$saveDir    = UPLOAD_PATH.$this->saveDir.$this->childDir; //设定上传目录,全路径
		$saveDirUrl = UPLOAD_URL.$this->saveDir.$this->childDir; //URL访问的路径
		if (!is_dir($saveDir)) {
			if (!mkdir($saveDir, 0755, true)) {
				$this->error = '上传目录 '.$saveDir.' 不存在';
				return false;
			}
		}
		if (!is_writeable($saveDir)) {
			$this->error = '上传目录 '.$saveDir.' 不可写';
			return false;
		}

		$uploadFileInfo = $_FILES[$this->postName]; //客户端上传来的的文件信息
		$fileInfo       = array(); //分析后的上传文件信息
		if (!empty($uploadFileInfo['name'])) {
			$fileInfo['extension']  = $this->getExt($uploadFileInfo['name']); //后缀
			$fileInfo['saveDir']    = $saveDir; //上传目录
			$fileInfo['saveDirUrl'] = $saveDirUrl; //url访问的url目录
			$_img_name              = substr(time(), -8).'-'.$this->loveName().'.'.$fileInfo['extension']; //保存的文件名
			$fileInfo['imgName']    = $_img_name;
			$fileInfo['saveName']   = $saveDir.'/'.$_img_name;
			$fileInfo['imageUrl']   = $saveDirUrl.'/'.$_img_name;
			$fileInfo['size']       = $uploadFileInfo['size'];
			$fileInfo['error']      = $uploadFileInfo['error'];
			$fileInfo['type']       = $uploadFileInfo['type'];
			$fileInfo['tmp_name']   = $uploadFileInfo['tmp_name'];
			$fileInfo['status']     = 1;
			//检查上传文件
			if (!$this->check($fileInfo)) {
				return false;
			}
			//保存上传文件
			if (!$this->save($fileInfo)) {
				return false;
			}
			return $fileInfo;
		} else {
			$this->error = '没有选择上传文件';
			return false;
		}
	}


	//检查上传的文件
	private function check($fileInfo) {
		if ($fileInfo['error'] !== 0) {
			$this->errorInfo($fileInfo['error']);
			return false;
		}
		if ($fileInfo['size'] > $this->maxSize && -1 != $this->maxSize) {
			$this->error = '上传文件大小不符！';
			return false;
		}
		if ($this->allowExts !== false && !in_array(strtolower($fileInfo['extension']), $this->allowExts, true)) {
			$this->error = '上传文件类型不允许';
			return false;
		}
		if (!is_uploaded_file($fileInfo['tmp_name'])) {
			$this->error = '非法上传文件！';
			return false;
		}
		return true;
	}

	//取得最后一次错误信息
	public function getErrorMsg() {
		return $this->error;
	}

	//取得上传文件的后缀
	private function getExt($filename) {
		$pathinfo = pathinfo($filename);
		return $pathinfo['extension'];
	}

	//获取错误代码信息 上传的错误代码
	private function errorInfo($errorNo) {
		switch ($errorNo) {
			case 1:
				$this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
				break;
			case 2:
				$this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
				break;
			case 3:
				$this->error = '文件只有部分被上传';
				break;
			case 4:
				$this->error = '没有文件被上传';
				break;
			case 6:
				$this->error = '找不到临时文件夹';
				break;
			case 7:
				$this->error = '文件写入失败';
				break;
			default:
				$this->error = '未知上传错误！';
		}
		return;
	}

	//随机生成令人兴奋的词语
	private function loveName() {
		$arr = array(
			'kwx',
			'wgb',
			'love',
			'life',
			'goodDay',
			'greatWork',
			'cheer',
			'happiness',
			'joyful',
			'loveKwx',
			'loveWgb',
			'kwxLoveYouForever',
			'kouwenxia',
			'loveKouwenxia',
			'loveYou',
			'loveYouForever',
			'loveLoveLove',
			'forever'
		);
		return $arr[mt_rand(0, count($arr) - 1)];
	}
}
