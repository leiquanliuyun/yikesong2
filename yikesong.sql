/*
Navicat MySQL Data Transfer

Source Server         : leiquan
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : yikesong

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-09-11 10:12:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cmf_ad
-- ----------------------------
DROP TABLE IF EXISTS `cmf_ad`;
CREATE TABLE `cmf_ad` (
  `ad_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `ad_name` varchar(255) NOT NULL COMMENT '广告名称',
  `ad_content` text COMMENT '广告内容',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  PRIMARY KEY (`ad_id`),
  KEY `ad_name` (`ad_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_ad
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_asset
-- ----------------------------
DROP TABLE IF EXISTS `cmf_asset`;
CREATE TABLE `cmf_asset` (
  `aid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户 id',
  `key` varchar(50) NOT NULL COMMENT '资源 key',
  `filename` varchar(50) DEFAULT NULL COMMENT '文件名',
  `filesize` int(11) DEFAULT NULL COMMENT '文件大小,单位Byte',
  `filepath` varchar(200) NOT NULL COMMENT '文件路径，相对于 upload 目录，可以为 url',
  `uploadtime` int(11) NOT NULL COMMENT '上传时间',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1：可用，0：删除，不可用',
  `meta` text COMMENT '其它详细信息，JSON格式',
  `suffix` varchar(50) DEFAULT NULL COMMENT '文件后缀名，不包括点',
  `download_times` int(11) NOT NULL DEFAULT '0' COMMENT '下载次数',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源表';

-- ----------------------------
-- Records of cmf_asset
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_auth_access
-- ----------------------------
DROP TABLE IF EXISTS `cmf_auth_access`;
CREATE TABLE `cmf_auth_access` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色',
  `rule_name` varchar(255) NOT NULL COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) DEFAULT NULL COMMENT '权限规则分类，请加应用前缀,如admin_',
  KEY `role_id` (`role_id`),
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限授权表';

-- ----------------------------
-- Records of cmf_auth_access
-- ----------------------------
INSERT INTO `cmf_auth_access` VALUES ('3', 'admin/fiscal/index', 'admin_url');

-- ----------------------------
-- Table structure for cmf_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `cmf_auth_rule`;
CREATE TABLE `cmf_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` varchar(30) NOT NULL DEFAULT '1' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `param` varchar(255) DEFAULT NULL COMMENT '额外url参数',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=210 DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
-- Records of cmf_auth_rule
-- ----------------------------
INSERT INTO `cmf_auth_rule` VALUES ('1', 'Admin', 'admin_url', 'admin/content/default', null, '内容管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('2', 'Api', 'admin_url', 'api/guestbookadmin/index', null, '所有留言', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('3', 'Api', 'admin_url', 'api/guestbookadmin/delete', null, '删除网站留言', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('4', 'Comment', 'admin_url', 'comment/commentadmin/index', null, '评论管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('5', 'Comment', 'admin_url', 'comment/commentadmin/delete', null, '删除评论', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('6', 'Comment', 'admin_url', 'comment/commentadmin/check', null, '评论审核', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('7', 'Portal', 'admin_url', 'portal/adminpost/index', null, '文章管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('8', 'Portal', 'admin_url', 'portal/adminpost/listorders', null, '文章排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('9', 'Portal', 'admin_url', 'portal/adminpost/top', null, '文章置顶', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('10', 'Portal', 'admin_url', 'portal/adminpost/recommend', null, '文章推荐', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('11', 'Portal', 'admin_url', 'portal/adminpost/move', null, '批量移动', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('12', 'Portal', 'admin_url', 'portal/adminpost/check', null, '文章审核', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('13', 'Portal', 'admin_url', 'portal/adminpost/delete', null, '删除文章', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('14', 'Portal', 'admin_url', 'portal/adminpost/edit', null, '编辑文章', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('15', 'Portal', 'admin_url', 'portal/adminpost/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('16', 'Portal', 'admin_url', 'portal/adminpost/add', null, '添加文章', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('17', 'Portal', 'admin_url', 'portal/adminpost/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('18', 'Portal', 'admin_url', 'portal/adminterm/index', null, '分类管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('19', 'Portal', 'admin_url', 'portal/adminterm/listorders', null, '文章分类排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('20', 'Portal', 'admin_url', 'portal/adminterm/delete', null, '删除分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('21', 'Portal', 'admin_url', 'portal/adminterm/edit', null, '编辑分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('22', 'Portal', 'admin_url', 'portal/adminterm/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('23', 'Portal', 'admin_url', 'portal/adminterm/add', null, '添加分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('24', 'Portal', 'admin_url', 'portal/adminterm/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('25', 'Portal', 'admin_url', 'portal/adminpage/index', null, '页面管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('26', 'Portal', 'admin_url', 'portal/adminpage/listorders', null, '页面排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('27', 'Portal', 'admin_url', 'portal/adminpage/delete', null, '删除页面', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('28', 'Portal', 'admin_url', 'portal/adminpage/edit', null, '编辑页面', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('29', 'Portal', 'admin_url', 'portal/adminpage/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('30', 'Portal', 'admin_url', 'portal/adminpage/add', null, '添加页面', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('31', 'Portal', 'admin_url', 'portal/adminpage/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('32', 'Admin', 'admin_url', 'admin/recycle/default', null, '回收站', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('33', 'Portal', 'admin_url', 'portal/adminpost/recyclebin', null, '文章回收', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('34', 'Portal', 'admin_url', 'portal/adminpost/restore', null, '文章还原', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('35', 'Portal', 'admin_url', 'portal/adminpost/clean', null, '彻底删除', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('36', 'Portal', 'admin_url', 'portal/adminpage/recyclebin', null, '页面回收', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('37', 'Portal', 'admin_url', 'portal/adminpage/clean', null, '彻底删除', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('38', 'Portal', 'admin_url', 'portal/adminpage/restore', null, '页面还原', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('39', 'Admin', 'admin_url', 'admin/extension/default', null, '扩展工具', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('40', 'Admin', 'admin_url', 'admin/backup/default', null, '备份管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('41', 'Admin', 'admin_url', 'admin/backup/restore', null, '数据还原', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('42', 'Admin', 'admin_url', 'admin/backup/index', null, '数据备份', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('43', 'Admin', 'admin_url', 'admin/backup/index_post', null, '提交数据备份', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('44', 'Admin', 'admin_url', 'admin/backup/download', null, '下载备份', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('45', 'Admin', 'admin_url', 'admin/backup/del_backup', null, '删除备份', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('46', 'Admin', 'admin_url', 'admin/backup/import', null, '数据备份导入', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('47', 'Admin', 'admin_url', 'admin/plugin/index', null, '插件管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('48', 'Admin', 'admin_url', 'admin/plugin/toggle', null, '插件启用切换', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('49', 'Admin', 'admin_url', 'admin/plugin/setting', null, '插件设置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('50', 'Admin', 'admin_url', 'admin/plugin/setting_post', null, '插件设置提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('51', 'Admin', 'admin_url', 'admin/plugin/install', null, '插件安装', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('52', 'Admin', 'admin_url', 'admin/plugin/uninstall', null, '插件卸载', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('53', 'Admin', 'admin_url', 'admin/slide/default', null, '幻灯片', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('54', 'Admin', 'admin_url', 'admin/slide/index', null, '幻灯片管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('55', 'Admin', 'admin_url', 'admin/slide/listorders', null, '幻灯片排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('56', 'Admin', 'admin_url', 'admin/slide/toggle', null, '幻灯片显示切换', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('57', 'Admin', 'admin_url', 'admin/slide/delete', null, '删除幻灯片', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('58', 'Admin', 'admin_url', 'admin/slide/edit', null, '编辑幻灯片', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('59', 'Admin', 'admin_url', 'admin/slide/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('60', 'Admin', 'admin_url', 'admin/slide/add', null, '添加幻灯片', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('61', 'Admin', 'admin_url', 'admin/slide/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('62', 'Admin', 'admin_url', 'admin/slidecat/index', null, '幻灯片分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('63', 'Admin', 'admin_url', 'admin/slidecat/delete', null, '删除分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('64', 'Admin', 'admin_url', 'admin/slidecat/edit', null, '编辑分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('65', 'Admin', 'admin_url', 'admin/slidecat/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('66', 'Admin', 'admin_url', 'admin/slidecat/add', null, '添加分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('67', 'Admin', 'admin_url', 'admin/slidecat/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('68', 'Admin', 'admin_url', 'admin/ad/index', null, '网站广告', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('69', 'Admin', 'admin_url', 'admin/ad/toggle', null, '广告显示切换', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('70', 'Admin', 'admin_url', 'admin/ad/delete', null, '删除广告', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('71', 'Admin', 'admin_url', 'admin/ad/edit', null, '编辑广告', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('72', 'Admin', 'admin_url', 'admin/ad/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('73', 'Admin', 'admin_url', 'admin/ad/add', null, '添加广告', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('74', 'Admin', 'admin_url', 'admin/ad/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('75', 'Admin', 'admin_url', 'admin/link/index', null, '友情链接', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('76', 'Admin', 'admin_url', 'admin/link/listorders', null, '友情链接排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('77', 'Admin', 'admin_url', 'admin/link/toggle', null, '友链显示切换', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('78', 'Admin', 'admin_url', 'admin/link/delete', null, '删除友情链接', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('79', 'Admin', 'admin_url', 'admin/link/edit', null, '编辑友情链接', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('80', 'Admin', 'admin_url', 'admin/link/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('81', 'Admin', 'admin_url', 'admin/link/add', null, '添加友情链接', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('82', 'Admin', 'admin_url', 'admin/link/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('83', 'Api', 'admin_url', 'api/oauthadmin/setting', null, '第三方登陆', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('84', 'Api', 'admin_url', 'api/oauthadmin/setting_post', null, '提交设置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('85', 'Admin', 'admin_url', 'admin/menu/default', null, '菜单管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('86', 'Admin', 'admin_url', 'admin/navcat/default1', null, '前台菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('87', 'Admin', 'admin_url', 'admin/nav/index', null, '菜单管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('88', 'Admin', 'admin_url', 'admin/nav/listorders', null, '前台导航排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('89', 'Admin', 'admin_url', 'admin/nav/delete', null, '删除菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('90', 'Admin', 'admin_url', 'admin/nav/edit', null, '编辑菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('91', 'Admin', 'admin_url', 'admin/nav/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('92', 'Admin', 'admin_url', 'admin/nav/add', null, '添加菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('93', 'Admin', 'admin_url', 'admin/nav/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('94', 'Admin', 'admin_url', 'admin/navcat/index', null, '菜单分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('95', 'Admin', 'admin_url', 'admin/navcat/delete', null, '删除分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('96', 'Admin', 'admin_url', 'admin/navcat/edit', null, '编辑分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('97', 'Admin', 'admin_url', 'admin/navcat/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('98', 'Admin', 'admin_url', 'admin/navcat/add', null, '添加分类', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('99', 'Admin', 'admin_url', 'admin/navcat/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('100', 'Admin', 'admin_url', 'admin/menu/index', null, '后台菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('101', 'Admin', 'admin_url', 'admin/menu/add', null, '添加菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('102', 'Admin', 'admin_url', 'admin/menu/add_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('103', 'Admin', 'admin_url', 'admin/menu/listorders', null, '后台菜单排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('104', 'Admin', 'admin_url', 'admin/menu/export_menu', null, '菜单备份', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('105', 'Admin', 'admin_url', 'admin/menu/edit', null, '编辑菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('106', 'Admin', 'admin_url', 'admin/menu/edit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('107', 'Admin', 'admin_url', 'admin/menu/delete', null, '删除菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('108', 'Admin', 'admin_url', 'admin/menu/lists', null, '所有菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('109', 'Admin', 'admin_url', 'admin/setting/default', null, '设置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('110', 'Admin', 'admin_url', 'admin/setting/userdefault', null, '个人信息', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('111', 'Admin', 'admin_url', 'admin/user/userinfo', null, '修改信息', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('112', 'Admin', 'admin_url', 'admin/user/userinfo_post', null, '修改信息提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('113', 'Admin', 'admin_url', 'admin/setting/password', null, '修改密码', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('114', 'Admin', 'admin_url', 'admin/setting/password_post', null, '提交修改', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('115', 'Admin', 'admin_url', 'admin/setting/site', null, '网站信息', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('116', 'Admin', 'admin_url', 'admin/setting/site_post', null, '提交修改', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('117', 'Admin', 'admin_url', 'admin/route/index', null, '路由列表', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('118', 'Admin', 'admin_url', 'admin/route/add', null, '路由添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('119', 'Admin', 'admin_url', 'admin/route/add_post', null, '路由添加提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('120', 'Admin', 'admin_url', 'admin/route/edit', null, '路由编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('121', 'Admin', 'admin_url', 'admin/route/edit_post', null, '路由编辑提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('122', 'Admin', 'admin_url', 'admin/route/delete', null, '路由删除', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('123', 'Admin', 'admin_url', 'admin/route/ban', null, '路由禁止', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('124', 'Admin', 'admin_url', 'admin/route/open', null, '路由启用', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('125', 'Admin', 'admin_url', 'admin/route/listorders', null, '路由排序', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('126', 'Admin', 'admin_url', 'admin/mailer/default', null, '邮箱配置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('127', 'Admin', 'admin_url', 'admin/mailer/index', null, 'SMTP配置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('128', 'Admin', 'admin_url', 'admin/mailer/index_post', null, '提交配置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('129', 'Admin', 'admin_url', 'admin/mailer/active', null, '注册邮件模板', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('130', 'Admin', 'admin_url', 'admin/mailer/active_post', null, '提交模板', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('131', 'Admin', 'admin_url', 'admin/setting/clearcache', null, '清除缓存', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('132', 'User', 'admin_url', 'user/indexadmin/default', null, '用户管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('133', 'User', 'admin_url', 'user/indexadmin/default1', null, '用户组', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('134', 'User', 'admin_url', 'user/indexadmin/index', null, '本站用户', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('135', 'User', 'admin_url', 'user/indexadmin/ban', null, '拉黑会员', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('136', 'User', 'admin_url', 'user/indexadmin/cancelban', null, '启用会员', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('137', 'User', 'admin_url', 'user/oauthadmin/index', null, '第三方用户', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('138', 'User', 'admin_url', 'user/oauthadmin/delete', null, '第三方用户解绑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('139', 'User', 'admin_url', 'user/indexadmin/default3', null, '管理组', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('140', 'Admin', 'admin_url', 'admin/rbac/index', null, '角色管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('141', 'Admin', 'admin_url', 'admin/rbac/member', null, '成员管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('142', 'Admin', 'admin_url', 'admin/rbac/authorize', null, '权限设置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('143', 'Admin', 'admin_url', 'admin/rbac/authorize_post', null, '提交设置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('144', 'Admin', 'admin_url', 'admin/rbac/roleedit', null, '编辑角色', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('145', 'Admin', 'admin_url', 'admin/rbac/roleedit_post', null, '提交编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('146', 'Admin', 'admin_url', 'admin/rbac/roledelete', null, '删除角色', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('147', 'Admin', 'admin_url', 'admin/rbac/roleadd', null, '添加角色', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('148', 'Admin', 'admin_url', 'admin/rbac/roleadd_post', null, '提交添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('149', 'Admin', 'admin_url', 'admin/user/index', null, '管理员', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('150', 'Admin', 'admin_url', 'admin/user/delete', null, '删除管理员', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('151', 'Admin', 'admin_url', 'admin/user/edit', null, '管理员编辑', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('152', 'Admin', 'admin_url', 'admin/user/edit_post', null, '编辑提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('153', 'Admin', 'admin_url', 'admin/user/add', null, '管理员添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('154', 'Admin', 'admin_url', 'admin/user/add_post', null, '添加提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('155', 'Admin', 'admin_url', 'admin/plugin/update', null, '插件更新', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('156', 'Admin', 'admin_url', 'admin/storage/index', null, '文件存储', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('157', 'Admin', 'admin_url', 'admin/storage/setting_post', null, '文件存储设置提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('158', 'Admin', 'admin_url', 'admin/slide/ban', null, '禁用幻灯片', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('159', 'Admin', 'admin_url', 'admin/slide/cancelban', null, '启用幻灯片', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('160', 'Admin', 'admin_url', 'admin/user/ban', null, '禁用管理员', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('161', 'Admin', 'admin_url', 'admin/user/cancelban', null, '启用管理员', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('162', 'Demo', 'admin_url', 'demo/adminindex/index', null, '', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('163', 'Demo', 'admin_url', 'demo/adminindex/last', null, '', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('166', 'Admin', 'admin_url', 'admin/mailer/test', null, '测试邮件', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('167', 'Admin', 'admin_url', 'admin/setting/upload', null, '上传设置', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('168', 'Admin', 'admin_url', 'admin/setting/upload_post', null, '上传设置提交', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('169', 'Portal', 'admin_url', 'portal/adminpost/copy', null, '文章批量复制', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('170', 'Admin', 'admin_url', 'admin/menu/backup_menu', null, '备份菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('171', 'Admin', 'admin_url', 'admin/menu/export_menu_lang', null, '导出后台菜单多语言包', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('172', 'Admin', 'admin_url', 'admin/menu/restore_menu', null, '还原菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('173', 'Admin', 'admin_url', 'admin/menu/getactions', null, '导入新菜单', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('174', 'Transfer', 'admin_url', 'transfer/transfer/index', null, '转让房屋租售管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('175', 'Admin', 'admin_url', 'admin/transefercontent/index', null, '内容管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('176', 'Admin', 'admin_url', 'admin/company/index', null, '公司情况管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('177', 'Admin', 'admin_url', 'admin/csituation/index', null, '内容管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('178', 'Admin', 'admin_url', 'admin/house/add', null, '添加', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('179', 'Admin', 'admin_url', 'admin/image/index', null, '首页轮播图管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('180', 'Admin', 'admin_url', 'admin/image/add', null, '添加图片', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('181', 'Admin', 'admin_url', 'admin/news/index', null, '资讯管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('182', 'Admin', 'admin_url', 'admin/service/index', null, '服务管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('183', 'Admin', 'admin_url', 'admin/serviceclass/index', null, '类目管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('184', 'Admin', 'admin_url', 'admin/servicecontent/index', null, '内容管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('185', 'Admin', 'admin_url', 'admin/house/index', null, '租房找房管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('186', 'Admin', 'admin_url', 'admin/houseclass/index', null, '分类筛选管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('187', 'Admin', 'admin_url', 'admin/housecontent/index', null, '内容管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('188', 'Admin', 'admin_url', 'admin/transercompany/index', null, '资质转让管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('189', 'Admin', 'admin_url', 'admin/companyclass/index', null, '分类筛选管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('190', 'Admin', 'admin_url', 'admin/companycontent/index', null, '内容管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('191', 'Admin', 'admin_url', 'admin/transfertmall/index', null, '天猫转让管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('192', 'Admin', 'admin_url', 'admin/tmallclass/index', null, '分类筛选管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('193', 'Admin', 'admin_url', 'admin/tmallcontent/index', null, '内容管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('194', 'Admin', 'admin_url', 'admin/coupon/index', null, '优惠券管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('195', 'Admin', 'admin_url', 'admin/housephone/index', null, '客服管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('196', 'Admin', 'admin_url', 'admin/companyphone/index', null, '客服管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('197', 'Admin', 'admin_url', 'admin/tmallphone/index', null, '客服管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('198', 'Admin', 'admin_url', 'admin/order/index', null, '预约订单管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('199', 'Admin', 'admin_url', 'admin/servicejd/index', null, '服务进度管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('200', 'Admin', 'admin_url', 'admin/pacter/index', null, '订单合同管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('201', 'Admin', 'admin_url', 'admin/member/index', null, '会员管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('202', 'Admin', 'admin_url', 'admin/remark/index', null, '需求管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('203', 'Admin', 'admin_url', 'admin/memberupload/index', null, '上传管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('204', 'Admin', 'admin_url', 'admin/withdraw/index', null, '提现订单管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('205', 'Admin', 'admin_url', 'admin/integral/index', null, '服务积分管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('206', 'Admin', 'admin_url', 'admin/fiscal/index', null, '财税管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('207', 'Admin', 'admin_url', 'admin/pactoption/index', null, '合同项管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('208', 'Admin', 'admin_url', 'admin/pacttemp/index', null, '合同模板管理', '1', '');
INSERT INTO `cmf_auth_rule` VALUES ('209', 'Admin', 'admin_url', 'admin/pact/index', null, '订单合同管理', '1', '');

-- ----------------------------
-- Table structure for cmf_collect
-- ----------------------------
DROP TABLE IF EXISTS `cmf_collect`;
CREATE TABLE `cmf_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `mod` varchar(50) DEFAULT NULL COMMENT '对应服务内容所在表',
  `service_id` int(11) DEFAULT NULL COMMENT '对应服务表servicecontent或housecontent的content_id',
  `status` tinyint(2) DEFAULT '1' COMMENT '收藏状态1收藏 0取消',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='用户收藏表';

-- ----------------------------
-- Records of cmf_collect
-- ----------------------------
INSERT INTO `cmf_collect` VALUES ('5', '1', 'servicecontent', '5', '1', '2017-06-02 15:08:11');
INSERT INTO `cmf_collect` VALUES ('1', '1', 'companycontent', '2', '1', '2017-06-02 15:16:30');
INSERT INTO `cmf_collect` VALUES ('10', '1', 'companycontent', '3', '1', '2017-06-22 17:18:15');
INSERT INTO `cmf_collect` VALUES ('12', '25', 'servicecontent', '6', '1', '2017-06-27 17:52:53');
INSERT INTO `cmf_collect` VALUES ('15', '20', 'servicecontent', '8', '1', '2017-09-08 17:14:54');
INSERT INTO `cmf_collect` VALUES ('25', '98', 'servicecontent', '8', '1', '2018-03-26 11:28:34');
INSERT INTO `cmf_collect` VALUES ('28', '99', 'servicecontent', '4', '1', '2018-03-28 17:27:41');
INSERT INTO `cmf_collect` VALUES ('31', '100', 'servicecontent', '7', '1', '2018-07-17 17:02:13');
INSERT INTO `cmf_collect` VALUES ('32', '123', 'Servicecontent', '4', '1', '2018-08-15 11:45:57');

-- ----------------------------
-- Table structure for cmf_comments
-- ----------------------------
DROP TABLE IF EXISTS `cmf_comments`;
CREATE TABLE `cmf_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_table` varchar(100) NOT NULL COMMENT '评论内容所在表，不带表前缀',
  `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论内容 id',
  `url` varchar(255) DEFAULT NULL COMMENT '原文地址',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '发表评论的用户id',
  `to_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被评论的用户id',
  `full_name` varchar(50) DEFAULT NULL COMMENT '评论者昵称',
  `email` varchar(255) DEFAULT NULL COMMENT '评论者邮箱',
  `createtime` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '评论时间',
  `content` text NOT NULL COMMENT '评论内容',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '评论类型；1实名评论',
  `parentid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '被回复的评论id',
  `path` varchar(500) DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '状态，1已审核，0未审核',
  PRIMARY KEY (`id`),
  KEY `comment_post_ID` (`post_id`),
  KEY `comment_approved_date_gmt` (`status`),
  KEY `comment_parent` (`parentid`),
  KEY `table_id_status` (`post_table`,`post_id`,`status`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of cmf_comments
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_common_action_log
-- ----------------------------
DROP TABLE IF EXISTS `cmf_common_action_log`;
CREATE TABLE `cmf_common_action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) DEFAULT '0' COMMENT '用户id',
  `object` varchar(100) DEFAULT NULL COMMENT '访问对象的id,格式：不带前缀的表名+id;如posts1表示xx_posts表里id为1的记录',
  `action` varchar(50) DEFAULT NULL COMMENT '操作名称；格式规定为：应用名+控制器+操作名；也可自己定义格式只要不发生冲突且惟一；',
  `count` int(11) DEFAULT '0' COMMENT '访问次数',
  `last_time` int(11) DEFAULT '0' COMMENT '最后访问的时间戳',
  `ip` varchar(15) DEFAULT NULL COMMENT '访问者最后访问ip',
  PRIMARY KEY (`id`),
  KEY `user_object_action` (`user`,`object`,`action`),
  KEY `user_object_action_ip` (`user`,`object`,`action`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='访问记录表';

-- ----------------------------
-- Records of cmf_common_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_companyclass
-- ----------------------------
DROP TABLE IF EXISTS `cmf_companyclass`;
CREATE TABLE `cmf_companyclass` (
  `sclass_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '筛选条件',
  `addTime` datetime DEFAULT NULL,
  PRIMARY KEY (`sclass_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='筛选条件';

-- ----------------------------
-- Records of cmf_companyclass
-- ----------------------------
INSERT INTO `cmf_companyclass` VALUES ('1', '实缴', '2017-06-01 13:16:01');
INSERT INTO `cmf_companyclass` VALUES ('3', '有资质', '2017-06-01 13:17:12');
INSERT INTO `cmf_companyclass` VALUES ('5', '有流水', '2017-06-22 17:00:23');
INSERT INTO `cmf_companyclass` VALUES ('6', '常规公司', '2017-06-22 17:00:35');
INSERT INTO `cmf_companyclass` VALUES ('7', '有税收', '2017-06-22 17:01:29');

-- ----------------------------
-- Table structure for cmf_companycontent
-- ----------------------------
DROP TABLE IF EXISTS `cmf_companycontent`;
CREATE TABLE `cmf_companycontent` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `picture` text COMMENT '图片',
  `filter_id` varchar(50) DEFAULT '' COMMENT '所属筛选条件ID',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `found_time` varchar(100) DEFAULT NULL COMMENT '成立时间',
  `aptitude` varchar(255) DEFAULT NULL COMMENT '资质情况',
  `tally` varchar(255) DEFAULT NULL COMMENT '记账情况',
  `solid_assets` varchar(255) DEFAULT NULL COMMENT '名下固产',
  `regmoney` char(50) DEFAULT NULL COMMENT '注册资金',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `price` double(11,2) DEFAULT NULL COMMENT '价格',
  `introduce_mess` text COMMENT '描述信息',
  `trade_type` text COMMENT '交易方式',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='租房找房内容表';

-- ----------------------------
-- Records of cmf_companycontent
-- ----------------------------
INSERT INTO `cmf_companycontent` VALUES ('2', '20180416\\bf50990153020093cf9c843cd569afec.png##20180416\\8efe006d9112b852774505cd10b764e6.png', '[4,5]', '杭州吊炸天公司', '2015年5月25日', '很有钱', '无', '游艇、豪宅、豪车', '1个亿', '很有钱', '59.00', '&lt;p&gt;很有钱&lt;br/&gt;&lt;/p&gt;', '&lt;p&gt;真得很有钱&lt;br/&gt;&lt;/p&gt;', '2017-06-01 14:41:09', null);
INSERT INTO `cmf_companycontent` VALUES ('3', '20180416\\bf50990153020093cf9c843cd569afec.png##20180416\\8efe006d9112b852774505cd10b764e6.png', '[4]', '天黑黑公司', '2005年5月25日', '有资质', '有', '500W', '100W', '一百万', '2000000.00', '<p>陈百万</p>', '<p>陈百万</p>', '2017-06-01 14:48:35', '2018-08-17 11:43:56');
INSERT INTO `cmf_companycontent` VALUES ('8', '20180416\\bf50990153020093cf9c843cd569afec.png##20180416\\8efe006d9112b852774505cd10b764e6.png', '[5]', '点点滴滴', '2016年7月7日', '有资质', '情况多', '南京紫峰大厦', '1亿', '玩玩', '1000000.00', '<p>大是大非</p>', '<p>色大概</p>', '2018-08-17 13:17:40', '2018-08-17 13:17:40');

-- ----------------------------
-- Table structure for cmf_coupon
-- ----------------------------
DROP TABLE IF EXISTS `cmf_coupon`;
CREATE TABLE `cmf_coupon` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) DEFAULT NULL COMMENT '所属服务名称',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `picture` text COMMENT '封面图',
  `price` double(11,2) NOT NULL COMMENT '优惠券可抵用价格',
  `num` int(11) DEFAULT NULL COMMENT '优惠券数量',
  `fullprice` double(11,2) DEFAULT NULL COMMENT '当产品价格达到多少时可用',
  `useday` smallint(3) DEFAULT NULL COMMENT '用户领取优惠券后可在多少天内使用',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1可使用 2暂停 ',
  `is_assign` tinyint(1) DEFAULT '0' COMMENT '0不指定 1指定',
  `assign_obj` varchar(150) DEFAULT '' COMMENT '指定对象',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='优惠券表';

-- ----------------------------
-- Records of cmf_coupon
-- ----------------------------
INSERT INTO `cmf_coupon` VALUES ('3', '公司注册', '100元优惠券', '&lt;p&gt;&lt;img src=&quot;/data/upload/ueditor/20170605/5934cd1572be6.png&quot; title=&quot;agree_label.png&quot; alt=&quot;agree_label.png&quot;/&gt;&lt;/p&gt;', '100.00', '47', '500.00', '5', '1', '0', '', '2017-06-05 11:16:50', null);
INSERT INTO `cmf_coupon` VALUES ('4', '增资验资', '200元优惠券', '&lt;p&gt;&lt;img src=&quot;/data/upload/ueditor/20170612/593e484ea09da.png&quot; title=&quot;icon_2.png&quot; alt=&quot;icon_2.png&quot;/&gt;&lt;/p&gt;', '0.01', '43', '1000.00', '5', '1', '0', '', '2017-06-12 15:52:59', null);
INSERT INTO `cmf_coupon` VALUES ('5', '政策规划', '300元优惠券', '&lt;p&gt;&lt;img src=&quot;/shoujiyikesong/data/upload/ueditor/20180725/5b58270c6ba85.jpg&quot; title=&quot;46ce49btk712.jpg&quot; alt=&quot;46ce49btk712.jpg&quot;/&gt;&lt;img src=&quot;/shoujiyikesong/data/upload/ueditor/20180725/5b582715758ca.jpg&quot; title=&quot;46ce49btk723.jpg&quot; alt=&quot;46ce49btk723.jpg&quot;/&gt;&lt;img src=&quot;/data/upload/ueditor/20170612/593e484ea09da.png&quot; title=&quot;icon_2.png&quot; alt=&quot;icon_2.png&quot;/&gt;&lt;/p&gt;', '300.00', '47', '1000.00', '5', '1', '0', '', '2017-06-12 15:52:59', null);
INSERT INTO `cmf_coupon` VALUES ('1', '财税管理', '400元优惠券', '20180809\\06ea18142c8beb140544d72e338a5e10.png', '400.00', '7', '1000.00', '5', '1', '0', '', '2017-06-12 15:52:59', '2018-08-21 10:15:50');
INSERT INTO `cmf_coupon` VALUES ('9', '公司注册', '哈哈哈', '20180809\\fd739352dcdee8143632aa420d57d3e8.png', '20.00', '6', '100.00', '3', '1', '0', '', '2018-08-09 11:08:58', '2018-08-09 11:08:58');
INSERT INTO `cmf_coupon` VALUES ('2', 'APP制作', '500元优惠券', '&lt;p&gt;&lt;img src=&quot;/data/upload/ueditor/20170612/593e484ea09da.png&quot; title=&quot;icon_2.png&quot; alt=&quot;icon_2.png&quot;/&gt;&lt;/p&gt;', '500.00', '50', '1000.00', '5', '1', '0', '', '2017-06-12 15:52:59', null);
INSERT INTO `cmf_coupon` VALUES ('6', '法律咨询', '600元优惠券', '&lt;p&gt;&lt;img src=&quot;/data/upload/ueditor/20170612/593e484ea09da.png&quot; title=&quot;icon_2.png&quot; alt=&quot;icon_2.png&quot;/&gt;&lt;/p&gt;', '600.00', '50', '1000.00', '5', '1', '0', '', '2017-06-12 15:52:59', null);
INSERT INTO `cmf_coupon` VALUES ('7', '租房找房', '700元优惠券', '&lt;p&gt;&lt;img src=&quot;/data/upload/ueditor/20170612/593e484ea09da.png&quot; title=&quot;icon_2.png&quot; alt=&quot;icon_2.png&quot;/&gt;&lt;/p&gt;', '700.00', '50', '1000.00', '5', '1', '0', '', '2017-06-12 15:52:59', null);
INSERT INTO `cmf_coupon` VALUES ('8', '豪车租赁', '800元优惠券', '&lt;p&gt;&lt;img src=&quot;/data/upload/ueditor/20170612/593e484ea09da.png&quot; title=&quot;icon_2.png&quot; alt=&quot;icon_2.png&quot;/&gt;&lt;/p&gt;', '800.00', '50', '1000.00', '5', '1', '0', '', '2017-06-12 15:52:59', null);
INSERT INTO `cmf_coupon` VALUES ('10', '增资验资', '急急急', '20180809\\7dfb1b23b49d22deba9a5e5bdf50f87c.png', '1000.00', '6', '20.00', '5', '1', '0', '', '2018-08-09 11:55:27', '2018-08-09 11:55:27');
INSERT INTO `cmf_coupon` VALUES ('11', '豪车租赁', '百度', '', '100.00', '2', '1000.00', '5', '1', '1', '[\"10\",\"11\"]', '2018-08-28 15:16:15', '2018-08-28 15:17:49');
INSERT INTO `cmf_coupon` VALUES ('12', '资质转让', '看看', '', '100.00', '2', '1000.00', '5', '1', '1', '[\"9\",\"10\"]', '2018-08-28 15:31:43', '2018-08-28 15:31:43');
INSERT INTO `cmf_coupon` VALUES ('13', '知识产权', '看看', '', '100.00', '2', '1000.00', '5', '1', '1', '[\"9\",\"10\"]', '2018-08-28 15:34:10', '2018-08-28 15:34:10');
INSERT INTO `cmf_coupon` VALUES ('14', '政策规划', '看看', '', '100.00', '2', '1000.00', '5', '1', '1', '[\"9\",\"10\"]', '2018-08-28 15:35:28', '2018-08-28 15:35:28');
INSERT INTO `cmf_coupon` VALUES ('15', '资质转让', '资质转让2', '20180416\\bf50990153020093cf9c843cd569afec.png', '500.00', '8', '2000.00', null, '1', '0', '', '2018-08-28 15:35:59', '2018-08-28 15:35:59');

-- ----------------------------
-- Table structure for cmf_csituation
-- ----------------------------
DROP TABLE IF EXISTS `cmf_csituation`;
CREATE TABLE `cmf_csituation` (
  `cs_id` int(10) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) DEFAULT NULL COMMENT '所属服务',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `pid` int(11) DEFAULT '0' COMMENT '上级ID',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`cs_id`)
) ENGINE=MyISAM AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COMMENT='公司情况类目选择表';

-- ----------------------------
-- Records of cmf_csituation
-- ----------------------------
INSERT INTO `cmf_csituation` VALUES ('40', '增资验资', '3000-10000万', '38', '2017-06-22 17:30:00');
INSERT INTO `cmf_csituation` VALUES ('39', '增资验资', '100-3000万', '38', '2017-06-22 17:29:49');
INSERT INTO `cmf_csituation` VALUES ('38', '增资验资', '增资金额', '0', '2017-06-22 17:29:38');
INSERT INTO `cmf_csituation` VALUES ('91', 'APP制作', '模板网站', '90', '2017-06-22 18:02:06');
INSERT INTO `cmf_csituation` VALUES ('90', 'APP制作', '官网定制', '0', '2017-06-22 18:01:39');
INSERT INTO `cmf_csituation` VALUES ('7', '公司注册', '注册资金', '0', '2017-06-02 11:29:49');
INSERT INTO `cmf_csituation` VALUES ('8', '公司注册', '100-500万', '7', '2017-06-02 11:30:10');
INSERT INTO `cmf_csituation` VALUES ('9', '公司注册', '1-100万', '7', '2017-06-02 11:30:35');
INSERT INTO `cmf_csituation` VALUES ('10', '公司注册', '500-1000万', '7', '2017-06-22 16:29:27');
INSERT INTO `cmf_csituation` VALUES ('11', '公司注册', '1000万以上', '7', '2017-06-22 16:29:37');
INSERT INTO `cmf_csituation` VALUES ('12', '公司注册', '是否记账', '0', '2017-06-22 16:29:54');
INSERT INTO `cmf_csituation` VALUES ('13', '公司注册', '是', '12', '2017-06-22 16:30:08');
INSERT INTO `cmf_csituation` VALUES ('14', '公司注册', '否', '12', '2017-06-22 16:30:39');
INSERT INTO `cmf_csituation` VALUES ('15', '公司注册', '税务种类', '0', '2017-06-22 16:31:38');
INSERT INTO `cmf_csituation` VALUES ('16', '公司注册', '小规模纳税人', '15', '2017-06-22 16:31:54');
INSERT INTO `cmf_csituation` VALUES ('17', '公司注册', '一般纳税人', '15', '2017-06-22 16:32:03');
INSERT INTO `cmf_csituation` VALUES ('18', '公司注册', '出口退税', '15', '2017-06-22 16:32:12');
INSERT INTO `cmf_csituation` VALUES ('19', '公司注册', '有无地址', '0', '2017-06-22 16:32:28');
INSERT INTO `cmf_csituation` VALUES ('20', '公司注册', '有', '19', '2017-06-22 16:32:35');
INSERT INTO `cmf_csituation` VALUES ('21', '公司注册', '无', '19', '2017-06-22 16:32:44');
INSERT INTO `cmf_csituation` VALUES ('22', '公司注册', '优质公司', '0', '2017-06-22 16:32:59');
INSERT INTO `cmf_csituation` VALUES ('23', '公司注册', '一般企业注册', '22', '2017-06-22 16:33:12');
INSERT INTO `cmf_csituation` VALUES ('24', '公司注册', '税收预估高的企业', '22', '2017-06-22 16:33:25');
INSERT INTO `cmf_csituation` VALUES ('25', '公司注册', '投资预估大的企业', '22', '2017-06-22 16:33:38');
INSERT INTO `cmf_csituation` VALUES ('26', '公司注册', '高新人才创业', '22', '2017-06-22 16:33:49');
INSERT INTO `cmf_csituation` VALUES ('27', '公司注册', '高新项创业', '22', '2017-06-22 16:34:02');
INSERT INTO `cmf_csituation` VALUES ('28', '公司注册', '大学生创业', '22', '2017-06-22 16:34:12');
INSERT INTO `cmf_csituation` VALUES ('29', '工商变更', '变更内容', '0', '2017-06-22 17:17:11');
INSERT INTO `cmf_csituation` VALUES ('30', '工商变更', '公司名称', '29', '2017-06-22 17:18:49');
INSERT INTO `cmf_csituation` VALUES ('31', '工商变更', '经营范围', '29', '2017-06-22 17:19:00');
INSERT INTO `cmf_csituation` VALUES ('32', '工商变更', '地址变更', '29', '2017-06-22 17:19:10');
INSERT INTO `cmf_csituation` VALUES ('33', '工商变更', '股东变更', '29', '2017-06-22 17:19:22');
INSERT INTO `cmf_csituation` VALUES ('34', '工商变更', '法人变更', '29', '2017-06-22 17:19:34');
INSERT INTO `cmf_csituation` VALUES ('35', '工商变更', '增资减资', '29', '2017-06-22 17:19:50');
INSERT INTO `cmf_csituation` VALUES ('36', '工商变更', '遗失补办', '29', '2017-06-22 17:20:03');
INSERT INTO `cmf_csituation` VALUES ('37', '工商变更', '公司注销', '29', '2017-06-22 17:20:12');
INSERT INTO `cmf_csituation` VALUES ('41', '增资验资', '10000万以上', '38', '2017-06-22 17:30:14');
INSERT INTO `cmf_csituation` VALUES ('42', '增资验资', '验资报告', '0', '2017-06-22 17:30:23');
INSERT INTO `cmf_csituation` VALUES ('43', '增资验资', '要', '42', '2017-06-22 17:30:31');
INSERT INTO `cmf_csituation` VALUES ('44', '增资验资', '不要', '42', '2017-06-22 17:30:39');
INSERT INTO `cmf_csituation` VALUES ('49', '增资验资', '是否新设', '0', '2017-06-22 17:31:18');
INSERT INTO `cmf_csituation` VALUES ('50', '增资验资', '是', '49', '2017-06-22 17:31:24');
INSERT INTO `cmf_csituation` VALUES ('51', '增资验资', '否', '49', '2017-06-22 17:31:31');
INSERT INTO `cmf_csituation` VALUES ('52', '政策规划', '缴税情况', '0', '2017-06-22 17:38:42');
INSERT INTO `cmf_csituation` VALUES ('53', '政策规划', '100万以上', '52', '2017-06-22 17:39:32');
INSERT INTO `cmf_csituation` VALUES ('54', '政策规划', '500万以上', '52', '2017-06-22 17:39:40');
INSERT INTO `cmf_csituation` VALUES ('55', '政策规划', '1000万以上', '52', '2017-06-22 17:39:49');
INSERT INTO `cmf_csituation` VALUES ('56', '政策规划', '5000万以上', '52', '2017-06-22 17:39:57');
INSERT INTO `cmf_csituation` VALUES ('57', '政策规划', '专利项目', '0', '2017-06-22 17:40:43');
INSERT INTO `cmf_csituation` VALUES ('58', '政策规划', '实用型', '57', '2017-06-22 17:41:00');
INSERT INTO `cmf_csituation` VALUES ('59', '政策规划', '外观型', '57', '2017-06-22 17:41:12');
INSERT INTO `cmf_csituation` VALUES ('60', '政策规划', '公司区域', '0', '2017-06-22 17:41:24');
INSERT INTO `cmf_csituation` VALUES ('61', '政策规划', '上城区', '60', '2017-06-22 17:41:45');
INSERT INTO `cmf_csituation` VALUES ('62', '政策规划', '下城区', '60', '2017-06-22 17:41:53');
INSERT INTO `cmf_csituation` VALUES ('63', '政策规划', '西湖区', '60', '2017-06-22 17:42:01');
INSERT INTO `cmf_csituation` VALUES ('64', '政策规划', '拱墅区', '60', '2017-06-22 17:42:08');
INSERT INTO `cmf_csituation` VALUES ('65', '政策规划', '江干区', '60', '2017-06-22 17:42:19');
INSERT INTO `cmf_csituation` VALUES ('66', '政策规划', '滨江区', '60', '2017-06-22 17:42:29');
INSERT INTO `cmf_csituation` VALUES ('67', '政策规划', '萧山区', '60', '2017-06-22 17:42:38');
INSERT INTO `cmf_csituation` VALUES ('68', '政策规划', '余杭区', '60', '2017-06-22 17:42:45');
INSERT INTO `cmf_csituation` VALUES ('69', '政策规划', '外地', '60', '2017-06-22 17:42:54');
INSERT INTO `cmf_csituation` VALUES ('70', '政策规划', '高新人才', '0', '2017-06-22 17:43:07');
INSERT INTO `cmf_csituation` VALUES ('71', '政策规划', '有', '70', '2017-06-22 17:43:14');
INSERT INTO `cmf_csituation` VALUES ('72', '政策规划', '无', '70', '2017-06-22 17:43:23');
INSERT INTO `cmf_csituation` VALUES ('73', '政策规划', '创业扶持', '0', '2017-06-22 17:43:33');
INSERT INTO `cmf_csituation` VALUES ('74', '政策规划', '大学生', '73', '2017-06-22 17:43:58');
INSERT INTO `cmf_csituation` VALUES ('75', '政策规划', ' 高新人才 ', '73', '2017-06-22 17:44:28');
INSERT INTO `cmf_csituation` VALUES ('76', '政策规划', '高新项目', '73', '2017-06-22 17:44:47');
INSERT INTO `cmf_csituation` VALUES ('77', '政策规划', '大额投资', '73', '2017-06-22 17:44:59');
INSERT INTO `cmf_csituation` VALUES ('78', '政策规划', '股东属地', '0', '2017-06-22 17:52:34');
INSERT INTO `cmf_csituation` VALUES ('79', '政策规划', '杭州本地', '78', '2017-06-22 17:52:43');
INSERT INTO `cmf_csituation` VALUES ('80', '政策规划', '杭州市外', '78', '2017-06-22 17:52:55');
INSERT INTO `cmf_csituation` VALUES ('81', '政策规划', '国外投资', '78', '2017-06-22 17:53:06');
INSERT INTO `cmf_csituation` VALUES ('82', '财税管理', '税务种类', '0', '2017-06-22 17:57:37');
INSERT INTO `cmf_csituation` VALUES ('83', '财税管理', '小规模纳税人', '82', '2017-06-22 17:57:49');
INSERT INTO `cmf_csituation` VALUES ('84', '财税管理', '一般纳税人', '82', '2017-06-22 17:58:30');
INSERT INTO `cmf_csituation` VALUES ('85', '财税管理', '出口退税', '82', '2017-06-22 17:58:43');
INSERT INTO `cmf_csituation` VALUES ('86', '财税管理', '付款方式', '0', '2017-06-22 17:58:54');
INSERT INTO `cmf_csituation` VALUES ('87', '财税管理', '季付', '86', '2017-06-22 17:59:06');
INSERT INTO `cmf_csituation` VALUES ('88', '财税管理', '半年付', '86', '2017-06-22 17:59:14');
INSERT INTO `cmf_csituation` VALUES ('89', '财税管理', '年付（8.3）折', '86', '2017-06-22 17:59:35');
INSERT INTO `cmf_csituation` VALUES ('92', 'APP制作', '手机网站', '90', '2017-06-22 18:02:18');
INSERT INTO `cmf_csituation` VALUES ('93', 'APP制作', '企业网站', '90', '2017-06-22 18:02:34');
INSERT INTO `cmf_csituation` VALUES ('94', 'APP制作', 'app制作', '0', '2017-06-22 18:03:13');
INSERT INTO `cmf_csituation` VALUES ('95', 'APP制作', 'app开发', '94', '2017-06-22 18:03:31');
INSERT INTO `cmf_csituation` VALUES ('96', 'APP制作', '微信开发', '0', '2017-06-22 18:03:44');
INSERT INTO `cmf_csituation` VALUES ('97', 'APP制作', '微信公众号开发', '96', '2017-06-22 18:04:00');
INSERT INTO `cmf_csituation` VALUES ('98', 'APP制作', '微信商城开发', '96', '2017-06-22 18:04:09');
INSERT INTO `cmf_csituation` VALUES ('99', 'APP制作', '微信三级分销', '96', '2017-06-22 18:04:26');
INSERT INTO `cmf_csituation` VALUES ('100', 'APP制作', '微信人人店', '96', '2017-06-22 18:04:37');
INSERT INTO `cmf_csituation` VALUES ('101', 'APP制作', '微信小程序', '96', '2017-06-22 18:04:45');
INSERT INTO `cmf_csituation` VALUES ('102', 'APP制作', '微信小游戏', '96', '2017-06-22 18:04:53');
INSERT INTO `cmf_csituation` VALUES ('103', '法律咨询', '法律服务', '0', '2017-06-22 18:07:51');
INSERT INTO `cmf_csituation` VALUES ('104', '法律咨询', '商业纠纷', '103', '2017-06-22 18:08:05');
INSERT INTO `cmf_csituation` VALUES ('105', '法律咨询', '债权债务', '103', '2017-06-22 18:08:17');
INSERT INTO `cmf_csituation` VALUES ('106', '法律咨询', '股权架构', '103', '2017-06-22 18:08:25');
INSERT INTO `cmf_csituation` VALUES ('107', '法律咨询', '知识产权', '103', '2017-06-22 18:08:38');
INSERT INTO `cmf_csituation` VALUES ('108', '网站制作', '网站定制', '0', '2017-06-23 09:26:01');
INSERT INTO `cmf_csituation` VALUES ('109', '网站制作', '模板网站', '108', '2017-06-23 09:26:21');
INSERT INTO `cmf_csituation` VALUES ('110', '网站制作', '手机网站', '108', '2017-06-23 09:26:42');
INSERT INTO `cmf_csituation` VALUES ('111', '网站制作', '企业网站', '108', '2017-06-23 09:26:57');
INSERT INTO `cmf_csituation` VALUES ('112', '网站制作', '商城类网站', '108', '2017-06-23 09:27:25');
INSERT INTO `cmf_csituation` VALUES ('113', '网站制作', '服务类网站', '108', '2017-06-23 09:28:33');
INSERT INTO `cmf_csituation` VALUES ('114', '网站制作', '门户网站', '108', '2017-06-23 09:28:55');
INSERT INTO `cmf_csituation` VALUES ('115', '网站制作', '外卖类网站', '108', '2017-06-23 09:29:55');
INSERT INTO `cmf_csituation` VALUES ('116', '网站制作', '自适应网站', '108', '2017-06-23 09:30:11');
INSERT INTO `cmf_csituation` VALUES ('117', '网站制作', '其他类型', '108', '2017-06-23 09:30:40');
INSERT INTO `cmf_csituation` VALUES ('118', '网站制作', '微信开发', '0', '2017-06-23 09:31:00');
INSERT INTO `cmf_csituation` VALUES ('119', '网站制作', '微信公众号开发', '118', '2017-06-23 09:31:17');
INSERT INTO `cmf_csituation` VALUES ('120', '网站制作', '微信商城开发', '118', '2017-06-23 09:31:47');
INSERT INTO `cmf_csituation` VALUES ('121', '网站制作', '微信三级分销', '118', '2017-06-23 09:32:02');
INSERT INTO `cmf_csituation` VALUES ('122', '网站制作', '微信人人店', '118', '2017-06-23 09:32:21');
INSERT INTO `cmf_csituation` VALUES ('123', '网站制作', '微信小程序', '118', '2017-06-23 09:32:46');
INSERT INTO `cmf_csituation` VALUES ('124', '网站制作', '微信小游戏', '118', '2017-06-23 09:32:58');

-- ----------------------------
-- Table structure for cmf_demand
-- ----------------------------
DROP TABLE IF EXISTS `cmf_demand`;
CREATE TABLE `cmf_demand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `content` text COMMENT '需求',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户需求表';

-- ----------------------------
-- Records of cmf_demand
-- ----------------------------
INSERT INTO `cmf_demand` VALUES ('2', '9', '222', '2017-06-26 15:12:27', null);

-- ----------------------------
-- Table structure for cmf_filter
-- ----------------------------
DROP TABLE IF EXISTS `cmf_filter`;
CREATE TABLE `cmf_filter` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `condition` varchar(100) DEFAULT NULL COMMENT '筛选条件',
  `service_name` varchar(100) DEFAULT '' COMMENT '所属服务',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='筛选条件';

-- ----------------------------
-- Records of cmf_filter
-- ----------------------------
INSERT INTO `cmf_filter` VALUES ('2', '一室一厅', '园区服务', '2017-05-31 16:10:59');
INSERT INTO `cmf_filter` VALUES ('3', '一室两厅', '园区服务', '2017-05-31 16:11:11');
INSERT INTO `cmf_filter` VALUES ('4', '有税收', '资质转让', '2018-08-16 19:21:19');
INSERT INTO `cmf_filter` VALUES ('5', '有流水', '资质转让', '2018-08-16 19:22:40');

-- ----------------------------
-- Table structure for cmf_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `cmf_guestbook`;
CREATE TABLE `cmf_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) NOT NULL COMMENT '留言者姓名',
  `email` varchar(100) NOT NULL COMMENT '留言者邮箱',
  `title` varchar(255) DEFAULT NULL COMMENT '留言标题',
  `msg` text NOT NULL COMMENT '留言内容',
  `createtime` datetime NOT NULL COMMENT '留言时间',
  `status` smallint(2) NOT NULL DEFAULT '1' COMMENT '留言状态，1：正常，0：删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言表';

-- ----------------------------
-- Records of cmf_guestbook
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_housecontent
-- ----------------------------
DROP TABLE IF EXISTS `cmf_housecontent`;
CREATE TABLE `cmf_housecontent` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `picture` text COMMENT '图片',
  `housetype` char(20) DEFAULT NULL COMMENT '房屋类型如办公楼、住宅楼',
  `filter_id` varchar(50) DEFAULT '' COMMENT '所属筛选条件ID',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `type` varchar(255) DEFAULT NULL COMMENT '房屋户型,如三室一厅两卫',
  `size` varchar(50) DEFAULT NULL COMMENT '房屋大小',
  `orientation` char(20) DEFAULT NULL COMMENT '房屋朝向 如：东南',
  `fixture` char(50) DEFAULT NULL COMMENT '装修方式 如：精装修',
  `rental_method` char(50) DEFAULT NULL COMMENT '租售方式 如：整租',
  `floor` char(20) DEFAULT NULL COMMENT '楼层',
  `price` double(11,2) DEFAULT NULL COMMENT '价格',
  `introduce_mess` text COMMENT '描述信息',
  `trade_type` text COMMENT '交易方式',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='资质转让内容表';

-- ----------------------------
-- Records of cmf_housecontent
-- ----------------------------
INSERT INTO `cmf_housecontent` VALUES ('1', '20180416\\bf50990153020093cf9c843cd569afec.png##20180416\\8efe006d9112b852774505cd10b764e6.png', '办公楼', '[2,3]', '憧憬花园', '一室一厅一卫', '20m?', '东南', '简装修', '整租', '18/45层', '1200.00', '<p>租房</p>', '<p>押一付一</p>', '2017-05-31 16:52:23', '2018-08-18 14:28:57');
INSERT INTO `cmf_housecontent` VALUES ('2', '20180416\\bf50990153020093cf9c843cd569afec.png##20180416\\8efe006d9112b852774505cd10b764e6.png', '办公楼', '[2]', '憧憬花园', '一室一厅一卫', '20m?', '东南', '简装修', '整租', '18/45层', '1600.00', '<p>租房</p>', '<p>押一付一</p>', '2017-05-31 16:52:23', '2018-08-18 14:28:57');

-- ----------------------------
-- Table structure for cmf_image
-- ----------------------------
DROP TABLE IF EXISTS `cmf_image`;
CREATE TABLE `cmf_image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COMMENT '图片地址',
  `addTime` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='轮播图表';

-- ----------------------------
-- Records of cmf_image
-- ----------------------------
INSERT INTO `cmf_image` VALUES ('5', '&lt;p&gt;&lt;img src=&quot;/wxyikesong/data/upload/ueditor/20180313/5aa7872b76074.jpg&quot; style=&quot;&quot; title=&quot;594b7bd7dee92.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/wxyikesong/data/upload/ueditor/20180313/5aa7872b879b8.jpg&quot; style=&quot;&quot; title=&quot;594b7c498e202.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '2018-03-13 16:09:21');
INSERT INTO `cmf_image` VALUES ('6', '&lt;p&gt;&lt;img src=&quot;/wxyikesong/data/upload/ueditor/20180313/5aa78ab948a0a.jpg&quot; title=&quot;594b7bd7dee92.jpg&quot; alt=&quot;594b7bd7dee92.jpg&quot;/&gt;&lt;/p&gt;', '2018-03-13 16:34:35');
INSERT INTO `cmf_image` VALUES ('7', '&lt;p&gt;&lt;img src=&quot;/shoujiyikesong/data/upload/ueditor/20180718/5b4ea230ad271.jpg&quot; title=&quot;46ce49btk716.jpg&quot; alt=&quot;46ce49btk716.jpg&quot;/&gt;&lt;img src=&quot;/shoujiyikesong/data/upload/ueditor/20180718/5b4eb3a3ae01b.png&quot; title=&quot;banner_index.png&quot; alt=&quot;banner_index.png&quot;/&gt;&lt;img src=&quot;/shoujiyikesong/data/upload/ueditor/20180718/5b4eb3b6b0782.png&quot; title=&quot;index_banner.png&quot; alt=&quot;index_banner.png&quot;/&gt;&lt;/p&gt;', '2018-03-13 16:34:47');

-- ----------------------------
-- Table structure for cmf_integral
-- ----------------------------
DROP TABLE IF EXISTS `cmf_integral`;
CREATE TABLE `cmf_integral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) DEFAULT NULL COMMENT '服务名称',
  `integral` double(11,2) NOT NULL COMMENT '返积分值，如：0.01',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='积分比例表';

-- ----------------------------
-- Records of cmf_integral
-- ----------------------------
INSERT INTO `cmf_integral` VALUES ('4', '天猫转让', '0.20', '2017-08-29 14:19:13', null);
INSERT INTO `cmf_integral` VALUES ('5', '资质转让', '0.30', '2017-10-23 18:23:06', null);
INSERT INTO `cmf_integral` VALUES ('6', '公司注册', '0.30', '2018-02-24 16:44:48', null);
INSERT INTO `cmf_integral` VALUES ('7', '财税管理', '0.10', '2018-02-24 16:45:15', null);
INSERT INTO `cmf_integral` VALUES ('8', '大规模广告', '0.60', '2018-07-31 13:35:44', null);
INSERT INTO `cmf_integral` VALUES ('10', '租房找房', '0.02', '2018-07-31 13:33:52', null);
INSERT INTO `cmf_integral` VALUES ('11', '税收规划', '0.60', '2018-07-31 13:35:32', null);

-- ----------------------------
-- Table structure for cmf_links
-- ----------------------------
DROP TABLE IF EXISTS `cmf_links`;
CREATE TABLE `cmf_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL COMMENT '友情链接地址',
  `link_name` varchar(255) NOT NULL COMMENT '友情链接名称',
  `link_image` varchar(255) DEFAULT NULL COMMENT '友情链接图标',
  `link_target` varchar(25) NOT NULL DEFAULT '_blank' COMMENT '友情链接打开方式',
  `link_description` text NOT NULL COMMENT '友情链接描述',
  `link_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `link_rating` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接评级',
  `link_rel` varchar(255) DEFAULT NULL COMMENT '链接与网站的关系',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
-- Records of cmf_links
-- ----------------------------
INSERT INTO `cmf_links` VALUES ('1', 'http://www.thinkcmf.com', 'ThinkCMF', '', '_blank', '', '1', '0', '', '0');

-- ----------------------------
-- Table structure for cmf_member
-- ----------------------------
DROP TABLE IF EXISTS `cmf_member`;
CREATE TABLE `cmf_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL COMMENT '随机字串',
  `nickname` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '昵称',
  `realname` char(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号码',
  `card_num` char(20) DEFAULT NULL COMMENT '身份证证件号码',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `picture` varchar(255) DEFAULT NULL COMMENT '用户头像地址',
  `id_photo` varchar(255) DEFAULT NULL COMMENT '身份证照片',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '微信二维码地址',
  `integral` double(11,2) DEFAULT '0.00' COMMENT '用户积分，可以用来兑换',
  `level_int` double(11,2) DEFAULT '0.00' COMMENT '等级积分（用来判断等级）',
  `open_id` varchar(255) DEFAULT NULL COMMENT '微信登录OPENID',
  `code` char(20) DEFAULT NULL COMMENT '唯一推广码',
  `pcode` char(20) DEFAULT NULL COMMENT '上级推广码',
  `liable` char(20) DEFAULT NULL COMMENT '上级最终邀请码',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别 1男 2女',
  `status` tinyint(1) DEFAULT '1' COMMENT '1正常 0删除',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `last_login_ip` varchar(100) CHARACTER SET utf8 DEFAULT '',
  `last_login_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=gbk COMMENT='用户表';

-- ----------------------------
-- Records of cmf_member
-- ----------------------------
INSERT INTO `cmf_member` VALUES ('9', '', '', '', 'fengbu', '测试', '18658875466', '340881199007185636', null, 'http://wx.qlogo.cn/mmopen/aKyDOs6gib7lLn2cLHsica7CXKs38HEHx56JAhaGhicLxTz9oV6o6rjVShDehLkMBHTzYo57230aZRfwJDc7K0jqJbSxAXMRZvr/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png', './data/upload/upfile/share/91498733950.png', '0.00', '0.00', 'oQHPOwI47pUSK2RY5KBlV-xlUx_k', '59423502e882e', null, '', '1', '1', '2017-06-15 15:19:30', null, '', null);
INSERT INTO `cmf_member` VALUES ('10', '', '', '', '松松小客服', null, null, null, null, 'http://wx.qlogo.cn/mmopen/QL9yXngJib0nObSZicac6h72WR2CgH4FeVFfdlUtLgxrkeoxY4wJbVWPXhFZP8iaBYnn8CrZibCVr6MibwicicyLspEDz6KARmYhKID/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwDH9l7DkrDzJu0ECE5Oh3Ic', '59423534110c9', null, '', null, '1', '2017-06-15 15:20:20', null, '', null);
INSERT INTO `cmf_member` VALUES ('11', '', '', '', '有梦就追', '郑蒙飞', '15906606006', '330821198707187277', '350308613@qq.com', 'http://wx.qlogo.cn/mmopen/aKyDOs6gib7kiauP6YzJT6jamjVW5hSq248aIBrYKLibjmOtYLMXpwwj7slOWfFjOxOicP3CdPAerFE17JSCn2nlEsD5LYY5tjiaW/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/111498733927.png', '0.00', '0.00', 'oQHPOwONY-pYQHJLAdUey8v1E5Po', '5942354caede4', null, '', '1', '1', '2017-06-15 15:20:44', null, '', null);
INSERT INTO `cmf_member` VALUES ('13', '', '', '', '上山打老虎????', null, null, null, null, 'http://wx.qlogo.cn/mmopen/T9LK7ceeHfzibciaiclWg8Xsmb6ePBY4Wy9lkNs9bBibJb8KzxyD5MF29Vyp9ibrq1MGXVWwbZWvDUalfVjm4BQ6a7A/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwNbG_-ay9tWzK8TAtplPmx0', '594a52e84757b', null, '', null, '1', '2017-06-21 19:05:12', null, '', null);
INSERT INTO `cmf_member` VALUES ('14', '', '', '', '维克软件@张', null, null, null, null, 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELs0xucoEFzVrBA4BDpzScaVRIp1d1hfjAjRjNhs2emT7uicngfy4SwsCWiaRHmqnkGhiaW9BbxHY2MQ/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwPsk3WiGo4G5IBMjH-7g6PY', '594b1950d7fc7', null, '', null, '1', '2017-06-22 09:11:44', null, '', null);
INSERT INTO `cmf_member` VALUES ('17', '', '', '', '风步豪车', '吴老师', '18106584599', '330682199109078229', null, 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM4e0wzXYicbWdwG5JnQ0lEaA9eduHwpYz1Uibea8IPZcNtCZY5AoKCibnqt88yz6ymicibSXxlFRQic3dKOOnQQeySJQjnynKicwHyYsA/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/171498793216.png', '0.00', '0.00', 'oQHPOwB4z-VeaKOhzsdiURSDDfjY', '594b92f7088be', null, '', '1', '1', '2017-06-22 17:50:47', null, '', null);
INSERT INTO `cmf_member` VALUES ('19', '', '', '', '九州疯小子', null, null, null, null, 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLDJuVZH45F0G8xA4Sg3BVlTxpnKClkbvy17MulRNorBQMu5B76uRib9fRFO0H7YIagFTe4Or7fIG2g/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/191498734016.png', '0.00', '0.00', 'oQHPOwA2R-p3JZAZvfKw2rwTqdJA', '594b9b60893b0', null, '', null, '1', '2017-06-22 18:26:40', null, '', null);
INSERT INTO `cmf_member` VALUES ('20', '', '', '', 'LY°', '徐', '18667046893', '330682199109088559', null, 'http://wx.qlogo.cn/mmopen/T9LK7ceeHfyxC2mZVONQFG7Jw9HsiaibtYq70p7D3RdLKWRLWmRShodaT6BphA9SzCHbnL46IFxsZHCXeWXKGjOI2YSzmwpZ2G/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/201504860692.png', '0.00', '0.00', 'oQHPOwB4u81m-eVr6vx5NqmeP_JQ', '594c69c2ad0e2', null, '', '2', '0', '2017-06-23 09:07:14', '2018-07-26 17:00:54', '', null);
INSERT INTO `cmf_member` VALUES ('21', '', '', '', '欧丽红', null, null, null, null, 'http://wx.qlogo.cn/mmopen/QL9yXngJib0lGIicSw3oDsFEhHmQlibQpoxDXLGqnUribXsoB9ia8Da9ZkicTSOWkMicJC2huHwhJdgKtp65CcH4ZfIfsia5U7vLg4wm/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '100.00', '100.00', 'oQHPOwLJIlTA0QJ0uRo23yWrEU6A', '594c6e287d0ef', null, '', null, '1', '2017-06-23 09:26:00', null, '', null);
INSERT INTO `cmf_member` VALUES ('23', '', '', '', '蓝律师', null, null, null, null, 'http://wx.qlogo.cn/mmopen/78EkX665csAhOicSTgBecr3y6wIUpraG78kXNwkwWsIr2G1VpoQHF2uTfrnibPzsFQGTiaTLx45qMtLaaDuI6DBSicfASQ1IhYpa/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwFWGyBhpUVfJrl2puHDT7Zw', '5950c652485d6', null, '', null, '1', '2017-06-26 16:31:14', null, '', null);
INSERT INTO `cmf_member` VALUES ('24', '', '', '', '郑蒙飞', null, null, null, null, 'http://wx.qlogo.cn/mmopen/aKyDOs6gib7lLn2cLHsica7EGiczwhAfF9WV9cBKzGxiczQXOy5f9cJnJjy7ic7cRBqwIpR4KC7ArxyhY6EnJ5pflyVqr5815eO3Y/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/241498736064.png', '0.00', '0.00', 'oQHPOwKwoBgEUdoG-LqSgXn8j2cc', '5950cd1812121', null, '', null, '1', '2017-06-26 17:00:08', null, '', null);
INSERT INTO `cmf_member` VALUES ('25', '', '', '', '心之所向...', null, null, null, null, 'http://wx.qlogo.cn/mmopen/aKyDOs6gib7lLn2cLHsica7K7yQdvsXK2ia7qGgl3x0tuNSrgiawIog2PvhZzqz9Hicbec8fCBx9WuH5Lbk9viaPlRRy0ib8ep7dk0J/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwJh5vn6KCaCESj-W6DRCykU', '595210715072a', null, '', null, '1', '2017-06-27 15:59:45', null, '', null);
INSERT INTO `cmf_member` VALUES ('26', '', '', '', 'WB007', null, null, null, null, 'http://wx.qlogo.cn/mmopen/QL9yXngJib0lGIicSw3oDsFFl964o0KYwHsBmXFCTnZrt4NrzSSUMjg1P6jSy0LKibibDFic7rX9JxSSOtDalZLLtQcf9mzBKwYNK/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwPHZ0YG0S1MN5xkBqrNCb1w', '59524131dbc03', null, '', null, '1', '2017-06-27 19:27:45', null, '', null);
INSERT INTO `cmf_member` VALUES ('27', '', '', '', 'A~四强', null, null, null, null, 'http://wx.qlogo.cn/mmopen/QL9yXngJib0lGIicSw3oDsFChw84XTNlT5vJ5q0UNOpjnPyia1QQfr1YV69HY0Ta3AxPVMDKOTpEiaaqeK6tvR66s34tJGQmNZ1p/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwEiBb9s1jaff85ujzy-4zWg', '5953094a3c097', null, '', null, '1', '2017-06-28 09:41:30', null, '', null);
INSERT INTO `cmf_member` VALUES ('28', '', '', '', '九州风神', null, null, null, null, 'http://wx.qlogo.cn/mmopen/T9LK7ceeHfxkLjibicGJwZdZmDkGUkgInTlCTxCAkgX6m3TRzCfNxibM6IBcTAmRaVkYVRBBMiaZgOCtsic3YuKBWAQ/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '300.00', '300.00', 'oQHPOwEZiM0wmfKaemhW6c6b2-TM', '5954e339c593e', '594b9b60893b0', '', null, '1', '2017-06-29 19:23:37', '2018-08-07 17:04:33', '', null);
INSERT INTO `cmf_member` VALUES ('29', '', '', '', '顾', null, null, null, null, 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM5KJuTDhCJttlUbuHBYqibughfFiaZM88FdMDa6ia6LO9pAW7icaJcqibqjibQMcj399zG36rhcCtIm9ibzg/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwIwalCa_2rHNQ_zXSMJ7xJI', '5955b2a9ce0c9', null, '', null, '1', '2017-06-30 10:08:41', null, '', null);
INSERT INTO `cmf_member` VALUES ('30', '', '', '', 'Mars、F', null, null, null, null, 'http://wx.qlogo.cn/mmopen/QL9yXngJib0lGIicSw3oDsFHanPOLic4jpY8LerLHSr5bJicIX4vLAAcmmeXh7IlIFH6IoGqCZ4W4PYlGfFjJveXtgib1RZWj1vd5/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwFsTPb9GMdk_xa3VoZs5f3s', '5956428574f64', null, '', null, '1', '2017-06-30 20:22:29', null, '', null);
INSERT INTO `cmf_member` VALUES ('32', '', '', '', 'A公司注册15958174098', null, null, null, null, 'http://wx.qlogo.cn/mmopen/QL9yXngJib0lGIicSw3oDsFF7HbSJjmmJDCk8X0XnzMl4gHFx6f5Uh0ibzVFNWibiczyvzNs4ic6yG0MM4lUdQsu53czzybJmOibZkb/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwJZ9myykG5hwZxAcI8GmJtM', '595caa0b81074', null, '', null, '1', '2017-07-05 16:57:47', null, '', null);
INSERT INTO `cmf_member` VALUES ('33', '', '', '', '女孩你知道吗', null, null, null, null, 'http://wx.qlogo.cn/mmopen/T9LK7ceeHfzicF8jyianyymY2WaB1yqVmXaFEYKUBicB9KXwdfiawjwOEdVUZgLBxEM0enib56A7wJL0h3XyYcwzqAg/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwGpvv3tWn6G9GM6GnqCxmEw', '595e73a6d0f22', null, '', null, '1', '2017-07-07 01:30:14', null, '', null);
INSERT INTO `cmf_member` VALUES ('34', '', '', '', '$_$', null, null, null, null, 'http://wx.qlogo.cn/mmopen/T9LK7ceeHfyxC2mZVONQFOpfMHtoPvyTcdWXKPBKoKrlzTngUV1qiboIJOwOP8iawxg9BNWWr93mGXPLohXJmeCQL0OtOkRNF4/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwB1J5WLzqaw2gObDn2kjD1Q', '5964a06fae695', '5942354caede4', '', null, '1', '2017-07-11 17:54:55', null, '', null);
INSERT INTO `cmf_member` VALUES ('52', '测试', 'd8197ca39975ab1ed0011d35d109b996', 'jcg005892kxu', null, null, null, null, null, '', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/521500372195.png', '0.00', '0.00', null, '596ddccdd9c1c', null, '', null, '1', '2017-07-18 18:02:53', null, '', null);
INSERT INTO `cmf_member` VALUES ('53', '', '', '', '小狮子', null, null, null, null, 'http://wx.qlogo.cn/mmhead/cH18XWgwVkTPia4FBAYia9uYj1toicNPYvaZI1syY35mHs/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwIB1F-4bXRVN-KLmtJvdOIs', '596ed443e7587', null, '', null, '1', '2017-07-19 11:38:43', null, '', null);
INSERT INTO `cmf_member` VALUES ('54', '', '', '', '楼裕', null, null, null, null, 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLBjCZZUvuiaT53Z3rftp3lMAXvfYTSib7RtpXgoUWMxrsyYH0KMZEgvibMA6NRRsDYcYzTEBScBEibHtA/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwJJYDrrQ28furX3yWWjJ42U', '596ed8923a5d7', null, '', null, '1', '2017-07-19 11:57:06', null, '', null);
INSERT INTO `cmf_member` VALUES ('55', '', '', '', '洪鹏', null, null, null, null, 'http://wx.qlogo.cn/mmhead/Q3auHgzwzM7UPHCd41tNg0QKu2oOJ0EVTnquYoEfOXqf4TyDZenjDA/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwPP6Tc1jiZWcgXfGm_74rLw', '5970606f74ec3', null, '', null, '1', '2017-07-20 15:49:03', null, '', null);
INSERT INTO `cmf_member` VALUES ('56', '', '', '', '代理腾讯邮箱朋友圈推广钱新礼', null, null, null, null, 'http://wx.qlogo.cn/mmopen/icYmgjchiclBlp4IUhwLHKCCRncQHDYBYcGlY6J6uI1o7FO6LdoPGgyeAEtl6RWz1rkTUN1KIyrWlwrm1aq0UsWdCc3HiaPq7ibU/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwP7vQl0vhL_ZSLzBflUXErQ', '597afe7e4ae6b', null, '', null, '1', '2017-07-28 17:06:06', null, '', null);
INSERT INTO `cmf_member` VALUES ('58', 'yuxixi', 'f514f65a4b3f99ef216ec6d595f953fe', 'wue365775dir', 'devil', '虞喜', '13838384438', '360782198910306617', null, 'http://wx.qlogo.cn/mmopen/T9LK7ceeHfyxC2mZVONQFCdI6xHam8fTBqHbVWCXTjJpQSgWKFe9GDDFJVCbJ2wQKiadJF0bhOUqUOSZp8BVpvJXRByLpibbXt/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/581503908742.png', '100.00', '100.00', 'oQHPOwF7Rg2qnh5pubszeCw7uiYU', '599e805264baa', '594c6e287d0ef', null, '1', '0', '2017-08-24 15:29:22', '2018-07-26 17:16:33', '', null);
INSERT INTO `cmf_member` VALUES ('59', null, null, null, 'fengbu', null, null, null, null, 'http://wx.qlogo.cn/mmopen/T9LK7ceeHfyxC2mZVONQFJejjSLBbnksoNeT1hkicNBuZpVHdZnh0aBkU87N9icO236vOibyQuQEAY2NibTjef3fKDH5sx8LGgbX/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwBgpRY2KDN-gpF_MtIuqA_4', '599fe8074f52a', null, null, null, '1', '2017-08-25 17:04:07', null, '', null);
INSERT INTO `cmf_member` VALUES ('61', null, null, null, '理', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJecSGcKOoNvh74oiaeJRUV1sAUN5gTnqQ9ibsibdcSA3ptoZWrq7blm0yAmnGSPPsIT6pib0c5tp4Gzg/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwGnM7zKdfJC_xFNFnRNEeWY', '59a8d0b860a3c', null, null, null, '1', '2017-09-01 11:15:04', null, '', null);
INSERT INTO `cmf_member` VALUES ('62', null, null, null, '行知-霍-万企动力', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJBxXlo43MsFlKcCWRhzEcfvQ4rPeTU0AQM73icjkrPeZelwpVRe3ItowXVzo4ONU3GRlxuEAbp1Hw/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwKpukgwov0BR_UlYyAy-z7U', '59a935277b6ce', null, null, null, '1', '2017-09-01 18:23:35', null, '', null);
INSERT INTO `cmf_member` VALUES ('63', null, null, null, '和と信', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/z9ic0cwCm6iavB4r8JqE4bum7OEsHicz2tHCHgkskM3zzNkAO1dHGfa1rLD721LuKIabOQe7Eo9ACckg5iaLia2kdgg/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwGY83F4uRngBoOZ0-k6icGE', '59aabf521381c', null, null, null, '1', '2017-09-02 22:25:22', null, '', null);
INSERT INTO `cmf_member` VALUES ('64', null, null, 'aou562162vve', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oaHxe0wX_uYJ9K1Jk3RLpU6n5Q8g', '59b25ea67b813', null, null, null, '1', '2017-09-08 17:11:02', null, '', null);
INSERT INTO `cmf_member` VALUES ('66', null, null, 'igb338352wew', 'devil', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIiaH3XRiayN0ufZEkhBslEp6qNR48Q3qpdADvuqiaOasMoR0No5n3xw3XxzJ8eAyXbXt8f39U70QXwg/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oaHxe03-vQXZPRlBH-7V2YLHZuB0', '59b5f3008bb73', null, null, null, '1', '2017-09-11 10:20:48', null, '', null);
INSERT INTO `cmf_member` VALUES ('67', null, null, null, '张立龙', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIdj0f6DTBZFkA7XbDOEIicyMV1EGHwdApev8x1ibRJIF8fRLQKqku1u1vXvJHP9SgbPiahuCIYibthicg/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwMiATdU9MNG51w5IrDxHAQI', '59b7707f5af6f', null, null, null, '1', '2017-09-12 13:28:31', null, '', null);
INSERT INTO `cmf_member` VALUES ('68', null, null, null, '给我滚', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKuHpibXVkhLSkMYibbUEbiao1iaXsuwee05NGLdvibxSkPlqr7We4XpUjI8Q4ibfEicHMyjgtF6QhmbUg8w/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/681505253068.png', '0.00', '0.00', 'oQHPOwNBovHYezjOrP8h_1qV5uAw', '59b856124193e', null, null, null, '1', '2017-09-13 05:48:02', null, '', null);
INSERT INTO `cmf_member` VALUES ('69', null, null, null, '热度', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eq0zXicZRuj8OxziamlmFTKd3MvmdoVHbROO7yD3zC6JZSBwgnoPo8PLpHE6njFLr8w8r1rdhBs2T1Q/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwEZ18MhiZid9_2eQvuZLiKA', '59bf9526573cc', null, null, null, '1', '2017-09-18 17:43:02', null, '', null);
INSERT INTO `cmf_member` VALUES ('70', null, null, null, '逆境而上', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epicqRmOVSZuTJ9E2tqvOsZfhyO0YASOVv6P23wibcX7ZnxmeianAcpXoOiczQU54OICQRA5x2LDWkTdw/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwL99llZZBc418dAs7oSWUsk', '59c624d263301', null, null, null, '1', '2017-09-23 17:09:38', null, '', null);
INSERT INTO `cmf_member` VALUES ('71', null, null, null, '蛋兒', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83ep4f3G3uxycaq9RnavGNYYEy338T2VXcJwCSO81QSp8wwP5Vm9RxX4UlhlPx9N4mLCXicqUbxTvyicA/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwGBS4hWWv90lauZM8q3NEaI', '59d2e8ae1ac82', null, null, null, '1', '2017-10-03 09:32:30', null, '', null);
INSERT INTO `cmf_member` VALUES ('72', null, null, null, '李小生', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIZHgkBj5hoc8QP3pMPJFgicCT8s8edTMBx1BMw6GjC5TWrKhasDPScfqakhmQGdYDOTgyCwSsfrtQ/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwArqfqtWHYb3EkhcfLEgjko', '59d68c0d69be7', null, null, null, '1', '2017-10-06 03:46:21', null, '', null);
INSERT INTO `cmf_member` VALUES ('73', null, null, null, '彭艳', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTISicP2rOtOBATMGzY2cvrHnRicW8LaQlJ3EIZTC1BroZQlBbFt3ibvuwsRKBubFLMMcNssrGoY54tgQ/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwOIn2FuI6lOjzGR53Yq2YNY', '59e9dfce42ee8', null, null, null, '1', '2017-10-20 19:36:46', null, '', null);
INSERT INTO `cmf_member` VALUES ('74', '小星星', 'fe04aee0c080920550a42238358f7042', 'lsg118660xkg', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '59f2afc24dace', null, null, null, '1', '2017-10-27 12:02:10', null, '', null);
INSERT INTO `cmf_member` VALUES ('75', '画眉yyy', 'd47f4976ab434178a283a89a6378f6f0', 'vcr990466ncg', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '59ffd0ba69b8a', null, null, null, '1', '2017-11-06 11:02:18', null, '', null);
INSERT INTO `cmf_member` VALUES ('76', null, null, 'ccn189976zvd', 'leme 乐忠锵', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqiaAnAcu8G5qJtUDmbeKfjM20icxdXY7PYllKlISE2aNvbbEWMZ1hU9H8YIwqpibPGmIIYO1hJB1Z7A/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oaHxe05MoMTxapyTmzIPSrVSYjks', '59ffe29832640', null, null, null, '1', '2017-11-06 12:18:32', null, '', null);
INSERT INTO `cmf_member` VALUES ('77', '巨人1987', '7f66136e10d903530b1867cedffe22ef', 'ozk438886kft', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5a0a596d732bc', null, null, null, '1', '2017-11-14 10:48:13', null, '', null);
INSERT INTO `cmf_member` VALUES ('78', null, null, null, 'wells', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIwoGy23G49g9O2USR18hxh1fCBgepzBARvD8pq7QaOzcE3DKicyAL2tudw3BicIZSb81eZMZ5biaJyg/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwJ4pe3rNLjOu5aRZ6G4EBiI', '5a0d31fe53f5a', null, null, null, '1', '2017-11-16 14:36:46', null, '', null);
INSERT INTO `cmf_member` VALUES ('79', null, null, null, '@明天的明天', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/ApCG3tqhJpR7zlkfmHEt3ticGLWicice7s9w1uqicXCHSjQduwib3K1J5yK2WqhkEzpicsTMDdu51CDmfj18jgv8ibJlA/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwKNUWXqqVekOBWrwdqBEZJ4', '5a14f950124ad', null, null, null, '1', '2017-11-22 12:13:04', null, '', null);
INSERT INTO `cmf_member` VALUES ('80', '22222222', '842315f1615e403d9fbb7454aca08271', 'bld813653vue', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5a1d1af74f69f', null, null, null, '1', '2017-11-28 16:14:47', null, '', null);
INSERT INTO `cmf_member` VALUES ('81', null, null, null, '陈先生', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJeElp8x6XibU21UaERgMFv1bCoZ32xAGgG0ywgNIb9KCnB7CkAHkYLvnHp3m8ibmtrtYzyjHgmcooA/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwJehkM_IqoQgPGaWKnAchMg', '5a28f76feeac0', null, null, null, '1', '2017-12-07 16:10:23', null, '', null);
INSERT INTO `cmf_member` VALUES ('82', null, null, null, 'william', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/lK7PW0QCghQT7I5MXrU3fPl8gqsU5PnxrYQDuUK20zcp8I3ty8O4elWfkqzkPiaoia3Y7n8A5LWUfcQgh8ykjWnw/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwH8689PYD_Z6rS-0-LsBN-8', '5a30e8cf43dc8', null, null, null, '1', '2017-12-13 16:46:07', null, '', null);
INSERT INTO `cmf_member` VALUES ('83', null, null, null, '星落', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKkRMza3f7jhLKyiaa2C0vEGUVjWn0CMPmUs0tM9gX1nyIkticOSuibCEuyxFjazKHxxCFWMjic4bxY9Q/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwGiQA0jn1qURirxFzcb_lEo', '5a447dd810874', null, null, null, '1', '2017-12-28 13:15:04', null, '', null);
INSERT INTO `cmf_member` VALUES ('84', null, null, null, '。明天', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIphPyew3wFaH5W1o4OFxWVDiaaFvqGibj5zuicX97Npwx1J9sRaKP5JJCKlibdibJhwKJA36mBphyctyw/0', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwAHOG5ZZj5uDorrgI1fSyP8', '5a44b1b9927ab', null, null, null, '1', '2017-12-28 16:56:25', null, '', null);
INSERT INTO `cmf_member` VALUES ('85', 'yeshikouqiang', '55ddc779b5caffd94428e48589d75918', 'myl562506ahp', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5a4d6ef4d5786', null, null, null, '1', '2018-01-04 08:01:56', null, '', null);
INSERT INTO `cmf_member` VALUES ('86', null, null, null, '当当我我', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83erGh2JQt8g9Lx8pVXk0jqbQgz26sSBViakh5Jiblm39nMlmNKQiciak2zoQR3GygJ7BvtHwpPOJG7wOvw/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwOBaaRxSwQHfajvd0Sk6MgM', '5a6805d950eef', '5b7100d51fa4c', null, null, '1', '2018-01-24 12:04:41', null, '', null);
INSERT INTO `cmf_member` VALUES ('87', null, null, null, '蒋启旦', null, null, null, null, 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKEVvHCkPalLvKiaZPdRXMLghS6ia3BdjnTxtLO3kRBPjuhXUUJ5ydrEk24YprrVCoWyVIygSsicelvg/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwKujDaQw68NNplXhBj3teKQ', '5a72bcd0d463b', '5b7100d51fa4c', null, null, '1', '2018-02-01 15:08:00', null, '', null);
INSERT INTO `cmf_member` VALUES ('88', '18237676638', '5c8708e94ea5ae664f99008245b661a0', 'lmq257296ldg', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5a9608fbd5ae4', '5b7100d51fa4c', null, null, '1', '2018-02-28 09:42:19', null, '', null);
INSERT INTO `cmf_member` VALUES ('89', null, null, null, 'Taurus.S-沈锴', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJm2hgmecBiauP6icFx2oyK2qY4FPuriag8J90N9RDj0diaXnlGrQbGOjHr3RFP7rI6Qu8cO2YSPrwdjA/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwIwToOG1zxGYLFq8WjneyWY', '5a968a820601a', '5b7100d51fa4c', null, null, '1', '2018-02-28 18:54:58', null, '', null);
INSERT INTO `cmf_member` VALUES ('90', null, null, null, 'A  A 朱先玍', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/78vDM2vOOAg9ZFVqlxKmGIY6fn3iaNTuv5qcv4rBD7s5NKqg8ica7k1dt7cz97iaSPnPtr7dtiaHIdaZn39H43JSgA/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwB4zrWw083uUfj39fVv-UBg', '5a968aa7bc5ec', '5b7100d51fa4c', null, null, '1', '2018-02-28 18:55:35', null, '', null);
INSERT INTO `cmf_member` VALUES ('91', null, null, null, '娜娜', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTI4tSatQ0rLPia5AsYBfcZeeIIM8Iia0qrdINTQncsOv4TuOEfAuvdN2K2BvMckj8QGKDWlia1NT4CXA/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwOVKuzlyaEwIuIYMq54Nsis', '5a96962f49add', '5b7100d51fa4c', null, null, '1', '2018-08-28 19:44:47', null, '', null);
INSERT INTO `cmf_member` VALUES ('92', null, null, null, '南', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/8r7SsWjzficuR2vJozSyBX9XMSRIHkR0kh2Zpov3pRSb2oTiapCp8uS3WFLP4QTavWicEJzgIpUrxtt09pgPkGNVQ/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwAN2QRAHNVh0BbPYa_QVrq4', '5a9699ad02b38', '5b7100d51fa4c', null, null, '1', '2018-02-28 19:59:41', null, '', null);
INSERT INTO `cmf_member` VALUES ('93', null, null, null, '故事里的事', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/vluhkVM9MRWra51ZRYeiaQxhyIyFic6NF4fP7C1GZicjg8RBdcQkKA7kxGCN9TPhzLUq0kpbhiaOMnlw5icGQD1pB4g/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwEKZLMrxSnJiTSpgpOvsrBs', '5a9699d2ce2b1', '5b7100d51fa4c', null, null, '1', '2018-02-28 20:00:18', null, '', null);
INSERT INTO `cmf_member` VALUES ('94', null, null, null, '德艺双馨的M老湿', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqY7LN8EUccmz1P568q3JpE4ibKIftySPAspVupTBPcXpAxF58JpdkaR9SJ3Rpo349exagapYPeWFg/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwKRSgWKdIri72e1Rr-oVN2E', '5a9a176ed8499', '5b7100d51fa4c', null, null, '1', '2018-08-03 11:33:02', null, '', null);
INSERT INTO `cmf_member` VALUES ('95', '13905809926', '9e1595fabd41feed444211a9ebda755f', 'bpw846496mzc', null, null, null, null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5a9a17eb08960', '5b862f3e40ac5', null, null, '1', '2018-03-03 11:35:07', null, '', null);
INSERT INTO `cmf_member` VALUES ('96', null, null, null, '仙仙', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTK4tsu9z0efKPibpC2HH9qut5oEUrPxOTdhHR59XKTvibtMkCZicZXV0qicxb5UichKEzH3xb2M0M2XghQ/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwBfLdFIY_V4KWT3PQ2ChMho', '5a9e31e4d3b52', '5b862f3e40ac5', null, null, '1', '2018-03-06 14:15:00', null, '', null);
INSERT INTO `cmf_member` VALUES ('97', null, null, null, '随心', null, null, null, null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/pTxDJGZiaQ8CTuicfcdf9lv9uZEziaArd06HliaI64TaJDxeTDjibMmERia76H0L08KpSt14WEZTqRtLAhV7Ng9RUib5w/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', 'oQHPOwGZvUHwhMcX4G5VNYtD4Ay8', '5a9e4311558f1', '5a9e58a17d656', null, null, '1', '2018-03-06 15:28:17', null, '', null);
INSERT INTO `cmf_member` VALUES ('98', null, null, null, '美萱Esther', '刘德华', '18806525464', '340881198006122358', '1015884825@qq.com', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLwRdo3K07ha6jeZsmbhRyJGudmLq80DciaNsTrS5oibSAqpMTGVTgrCGHzQiaicUzOEl3Kmp2EYX1ahA/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/981521102320.png', '0.00', '0.00', 'oQHPOwLb_vT_aE5DHx5T7joSDuC4', '5a9e58a17d656', '', '5a9e4311558f1', '1', '1', '2018-03-06 17:00:17', null, '', null);
INSERT INTO `cmf_member` VALUES ('99', null, null, null, '雪花神龙', '雷全', '18806526188', '340889199008172389', '1015884825@qq.com', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIWmibHlAAc57rUM4UKiaLez5KIxjKmPzLQjrrVWf0WdXe8agZSP55Xlx9cKiaaLXtmmtQzeibxMIt9Ow/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', './data/upload/upfile/share/991520996656.png', '0.00', '0.00', 'oQHPOwHB_TaiP9Tb9v-wx3Y46Rs8', '5a9fc07075626', '', '5954e339c593e', '1', '1', '2018-03-07 18:35:28', null, '', null);
INSERT INTO `cmf_member` VALUES ('100', 'leiquan', 'f2e583163bce5ce6f8274375cb97aff2', 'jww981897fzl', null, '雷时全', '18052638799', '340881199008256323', null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '100000.00', '0.00', null, '5b4da63d1a257', null, '', '1', '1', '2018-07-17 16:18:05', null, '', null);
INSERT INTO `cmf_member` VALUES ('123', null, 'e43dd24d3cb175af1a6505294febbffc', 'XqJ9XK', null, '雷全', '18806526189', '340881199008175616', null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '/uploads/images/share/1231534233854.png', '10000.00', '10000.00', null, '5b7100d51fa4c', '5a9fc07075626', null, '1', '1', '2018-08-13 11:53:57', '2018-08-16 14:24:08', '::1', '2018-08-13 18:40:22');
INSERT INTO `cmf_member` VALUES ('124', null, null, null, null, null, '18806526187', null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5b8525e8cba06', null, null, null, '1', '2018-08-28 18:37:28', '2018-08-28 18:37:28', '', null);
INSERT INTO `cmf_member` VALUES ('125', null, null, null, null, null, '18806526186', null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5b862e656ff05', '5a9e58a17d656', null, null, '1', '2018-08-29 13:25:57', '2018-08-29 13:25:57', '', null);
INSERT INTO `cmf_member` VALUES ('126', null, null, null, 'jianmo', '雷全', '18806526184', '340881199008175616', null, 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIWmibHlAAc57rUM4UKiaLez5KIxjKmPzLQjrrVWf0WdXe8agZSP55Xlx9cKiaaLXtmmtQzeibxMIt9Ow/132', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '29850.00', '29850.00', null, '5b862f3e40ac5', '5a9e58a17d656', null, '1', '1', '2018-08-29 13:29:34', '2018-08-29 17:09:22', '::1', '2018-08-29 13:54:13');
INSERT INTO `cmf_member` VALUES ('127', null, null, null, null, null, '18806526185', null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5b862fa832489', '5a9e58a17d656', null, null, '1', '2018-08-29 13:31:20', '2018-08-29 13:31:20', '', null);
INSERT INTO `cmf_member` VALUES ('128', null, null, null, null, null, '18806526182', null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5b862fdcd2714', '5a9e58a17d656', null, null, '1', '2018-08-29 13:32:12', '2018-08-29 13:32:12', '', null);
INSERT INTO `cmf_member` VALUES ('129', null, null, null, null, null, '18806526181', null, null, null, '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', null, '0.00', '0.00', null, '5b86300e70039', '5a9e58a17d656', null, null, '1', '2018-08-29 13:33:02', '2018-08-29 13:33:02', '', null);

-- ----------------------------
-- Table structure for cmf_member_coupon
-- ----------------------------
DROP TABLE IF EXISTS `cmf_member_coupon`;
CREATE TABLE `cmf_member_coupon` (
  `member_coupon_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) DEFAULT NULL COMMENT '优惠券ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `status` tinyint(1) DEFAULT '1' COMMENT '使用情况 1未使用 2已使用',
  `create_time` datetime DEFAULT NULL,
  `use_time` datetime DEFAULT NULL COMMENT '用户使用优惠券时间',
  `end_time` datetime DEFAULT NULL COMMENT '用户结束期限',
  PRIMARY KEY (`member_coupon_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='用户优惠券表';

-- ----------------------------
-- Records of cmf_member_coupon
-- ----------------------------
INSERT INTO `cmf_member_coupon` VALUES ('11', '4', '9', '1', '2017-06-13 11:15:11', null, '2017-06-20 11:15:11');
INSERT INTO `cmf_member_coupon` VALUES ('12', '4', '1', '1', '2017-06-26 15:12:44', null, '2017-07-01 15:12:44');
INSERT INTO `cmf_member_coupon` VALUES ('13', '4', '20', '1', '2017-06-26 16:20:36', null, '2017-07-01 16:20:36');
INSERT INTO `cmf_member_coupon` VALUES ('14', '5', '9', '2', '2017-06-26 16:25:41', null, '2017-07-01 16:25:41');
INSERT INTO `cmf_member_coupon` VALUES ('15', '1', '9', '1', '2017-08-25 15:59:45', null, '2017-08-30 15:59:45');
INSERT INTO `cmf_member_coupon` VALUES ('16', '4', '58', '1', '2017-08-28 16:25:13', null, '2017-09-02 16:25:13');
INSERT INTO `cmf_member_coupon` VALUES ('17', '4', '69', '1', '2017-09-19 08:46:03', null, '2017-09-24 08:46:03');
INSERT INTO `cmf_member_coupon` VALUES ('18', '5', '99', '1', '2018-03-26 08:46:03', null, '2018-03-31 08:46:03');
INSERT INTO `cmf_member_coupon` VALUES ('24', '3', '123', '1', '2018-08-15 16:25:57', null, '2018-08-29 16:25:57');
INSERT INTO `cmf_member_coupon` VALUES ('25', '4', '123', '1', '2018-08-15 16:30:46', null, '2018-08-29 16:30:46');
INSERT INTO `cmf_member_coupon` VALUES ('26', '5', '123', '2', '2018-08-15 16:32:51', '2018-08-15 16:42:51', '2018-08-20 16:32:51');
INSERT INTO `cmf_member_coupon` VALUES ('27', '4', '123', '1', '2018-08-20 16:30:46', '2018-08-21 16:20:30', '2018-08-29 16:30:46');
INSERT INTO `cmf_member_coupon` VALUES ('29', '5', '123', '1', '2018-08-27 19:14:42', null, '2018-09-01 19:14:42');
INSERT INTO `cmf_member_coupon` VALUES ('30', '14', '9', '1', '2018-08-28 15:35:28', null, '2018-09-02 15:35:28');
INSERT INTO `cmf_member_coupon` VALUES ('31', '14', '10', '1', '2018-08-28 15:35:28', null, '2018-11-02 15:35:28');
INSERT INTO `cmf_member_coupon` VALUES ('32', '15', '126', '1', '2018-08-28 15:35:28', null, '2018-11-02 15:35:28');
INSERT INTO `cmf_member_coupon` VALUES ('33', '12', '126', '1', '2018-08-28 15:35:28', null, '2018-11-02 15:35:28');

-- ----------------------------
-- Table structure for cmf_member_uploadinfo
-- ----------------------------
DROP TABLE IF EXISTS `cmf_member_uploadinfo`;
CREATE TABLE `cmf_member_uploadinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mId` int(11) DEFAULT NULL COMMENT '用户ID',
  `picture` varchar(255) DEFAULT NULL COMMENT '营业执照',
  `content` varchar(255) DEFAULT NULL COMMENT '用户选择的内容',
  `regTime` char(30) DEFAULT NULL COMMENT '注册日期',
  `supply` text COMMENT '补充说明',
  `addTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户上传数据表';

-- ----------------------------
-- Records of cmf_member_uploadinfo
-- ----------------------------
INSERT INTO `cmf_member_uploadinfo` VALUES ('2', '1', './data/upload/upfile/2017-06-26/5950b424e5892.PNG', '店铺,注册资金已到账,小规模', '2017-06-02', '123', '2017-06-26 15:13:40');

-- ----------------------------
-- Table structure for cmf_menu
-- ----------------------------
DROP TABLE IF EXISTS `cmf_menu`;
CREATE TABLE `cmf_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `app` varchar(30) NOT NULL DEFAULT '' COMMENT '应用名称app',
  `model` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称',
  `data` varchar(50) NOT NULL DEFAULT '' COMMENT '额外参数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单类型  1：权限认证+菜单；0：只作为菜单',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，1显示，0不显示',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `icon` varchar(50) DEFAULT NULL COMMENT '菜单图标',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parentid` (`parentid`),
  KEY `model` (`model`)
) ENGINE=MyISAM AUTO_INCREMENT=227 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of cmf_menu
-- ----------------------------
INSERT INTO `cmf_menu` VALUES ('1', '0', 'Admin', 'Content', 'default', '', '0', '0', '内容管理', 'th', '', '70');
INSERT INTO `cmf_menu` VALUES ('2', '1', 'Api', 'Guestbookadmin', 'index', '', '1', '1', '所有留言', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('3', '2', 'Api', 'Guestbookadmin', 'delete', '', '1', '0', '删除网站留言', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('4', '1', 'Comment', 'Commentadmin', 'index', '', '1', '1', '评论管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('5', '4', 'Comment', 'Commentadmin', 'delete', '', '1', '0', '删除评论', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('6', '4', 'Comment', 'Commentadmin', 'check', '', '1', '0', '评论审核', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('7', '1', 'Portal', 'AdminPost', 'index', '', '1', '1', '文章管理', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('8', '7', 'Portal', 'AdminPost', 'listorders', '', '1', '0', '文章排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('9', '7', 'Portal', 'AdminPost', 'top', '', '1', '0', '文章置顶', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('10', '7', 'Portal', 'AdminPost', 'recommend', '', '1', '0', '文章推荐', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('11', '7', 'Portal', 'AdminPost', 'move', '', '1', '0', '批量移动', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('12', '7', 'Portal', 'AdminPost', 'check', '', '1', '0', '文章审核', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('13', '7', 'Portal', 'AdminPost', 'delete', '', '1', '0', '删除文章', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('14', '7', 'Portal', 'AdminPost', 'edit', '', '1', '0', '编辑文章', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('15', '14', 'Portal', 'AdminPost', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('16', '7', 'Portal', 'AdminPost', 'add', '', '1', '0', '添加文章', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('17', '16', 'Portal', 'AdminPost', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('18', '1', 'Portal', 'AdminTerm', 'index', '', '0', '1', '分类管理', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('19', '18', 'Portal', 'AdminTerm', 'listorders', '', '1', '0', '文章分类排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('20', '18', 'Portal', 'AdminTerm', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('21', '18', 'Portal', 'AdminTerm', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('22', '21', 'Portal', 'AdminTerm', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('23', '18', 'Portal', 'AdminTerm', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('24', '23', 'Portal', 'AdminTerm', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('25', '1', 'Portal', 'AdminPage', 'index', '', '1', '1', '页面管理', '', '', '3');
INSERT INTO `cmf_menu` VALUES ('26', '25', 'Portal', 'AdminPage', 'listorders', '', '1', '0', '页面排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('27', '25', 'Portal', 'AdminPage', 'delete', '', '1', '0', '删除页面', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('28', '25', 'Portal', 'AdminPage', 'edit', '', '1', '0', '编辑页面', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('29', '28', 'Portal', 'AdminPage', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('30', '25', 'Portal', 'AdminPage', 'add', '', '1', '0', '添加页面', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('31', '30', 'Portal', 'AdminPage', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('32', '1', 'Admin', 'Recycle', 'default', '', '1', '1', '回收站', '', '', '4');
INSERT INTO `cmf_menu` VALUES ('33', '32', 'Portal', 'AdminPost', 'recyclebin', '', '1', '1', '文章回收', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('34', '33', 'Portal', 'AdminPost', 'restore', '', '1', '0', '文章还原', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('35', '33', 'Portal', 'AdminPost', 'clean', '', '1', '0', '彻底删除', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('36', '32', 'Portal', 'AdminPage', 'recyclebin', '', '1', '1', '页面回收', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('37', '36', 'Portal', 'AdminPage', 'clean', '', '1', '0', '彻底删除', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('38', '36', 'Portal', 'AdminPage', 'restore', '', '1', '0', '页面还原', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('39', '0', 'Admin', 'Extension', 'default', '', '0', '0', '扩展工具', 'cloud', '', '90');
INSERT INTO `cmf_menu` VALUES ('40', '39', 'Admin', 'Backup', 'default', '', '1', '0', '备份管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('41', '40', 'Admin', 'Backup', 'restore', '', '1', '1', '数据还原', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('42', '40', 'Admin', 'Backup', 'index', '', '1', '1', '数据备份', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('43', '42', 'Admin', 'Backup', 'index_post', '', '1', '0', '提交数据备份', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('44', '40', 'Admin', 'Backup', 'download', '', '1', '0', '下载备份', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('45', '40', 'Admin', 'Backup', 'del_backup', '', '1', '0', '删除备份', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('46', '40', 'Admin', 'Backup', 'import', '', '1', '0', '数据备份导入', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('47', '39', 'Admin', 'Plugin', 'index', '', '1', '1', '插件管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('48', '47', 'Admin', 'Plugin', 'toggle', '', '1', '0', '插件启用切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('49', '47', 'Admin', 'Plugin', 'setting', '', '1', '0', '插件设置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('50', '49', 'Admin', 'Plugin', 'setting_post', '', '1', '0', '插件设置提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('51', '47', 'Admin', 'Plugin', 'install', '', '1', '0', '插件安装', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('52', '47', 'Admin', 'Plugin', 'uninstall', '', '1', '0', '插件卸载', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('53', '39', 'Admin', 'Slide', 'default', '', '1', '1', '幻灯片', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('54', '53', 'Admin', 'Slide', 'index', '', '1', '1', '幻灯片管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('55', '54', 'Admin', 'Slide', 'listorders', '', '1', '0', '幻灯片排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('56', '54', 'Admin', 'Slide', 'toggle', '', '1', '0', '幻灯片显示切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('57', '54', 'Admin', 'Slide', 'delete', '', '1', '0', '删除幻灯片', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('58', '54', 'Admin', 'Slide', 'edit', '', '1', '0', '编辑幻灯片', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('59', '58', 'Admin', 'Slide', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('60', '54', 'Admin', 'Slide', 'add', '', '1', '0', '添加幻灯片', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('61', '60', 'Admin', 'Slide', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('62', '53', 'Admin', 'Slidecat', 'index', '', '1', '1', '幻灯片分类', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('63', '62', 'Admin', 'Slidecat', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('64', '62', 'Admin', 'Slidecat', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('65', '64', 'Admin', 'Slidecat', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('66', '62', 'Admin', 'Slidecat', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('67', '66', 'Admin', 'Slidecat', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('68', '39', 'Admin', 'Ad', 'index', '', '1', '1', '网站广告', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('69', '68', 'Admin', 'Ad', 'toggle', '', '1', '0', '广告显示切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('70', '68', 'Admin', 'Ad', 'delete', '', '1', '0', '删除广告', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('71', '68', 'Admin', 'Ad', 'edit', '', '1', '0', '编辑广告', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('72', '71', 'Admin', 'Ad', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('73', '68', 'Admin', 'Ad', 'add', '', '1', '0', '添加广告', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('74', '73', 'Admin', 'Ad', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('75', '39', 'Admin', 'Link', 'index', '', '0', '1', '友情链接', '', '', '3');
INSERT INTO `cmf_menu` VALUES ('76', '75', 'Admin', 'Link', 'listorders', '', '1', '0', '友情链接排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('77', '75', 'Admin', 'Link', 'toggle', '', '1', '0', '友链显示切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('78', '75', 'Admin', 'Link', 'delete', '', '1', '0', '删除友情链接', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('79', '75', 'Admin', 'Link', 'edit', '', '1', '0', '编辑友情链接', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('80', '79', 'Admin', 'Link', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('81', '75', 'Admin', 'Link', 'add', '', '1', '0', '添加友情链接', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('82', '81', 'Admin', 'Link', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('83', '39', 'Api', 'Oauthadmin', 'setting', '', '1', '1', '第三方登陆', 'leaf', '', '4');
INSERT INTO `cmf_menu` VALUES ('84', '83', 'Api', 'Oauthadmin', 'setting_post', '', '1', '0', '提交设置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('85', '0', 'Admin', 'Menu', 'default', '', '1', '1', '菜单管理', 'list', '', '50');
INSERT INTO `cmf_menu` VALUES ('86', '85', 'Admin', 'Navcat', 'default1', '', '1', '1', '前台菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('87', '86', 'Admin', 'Nav', 'index', '', '1', '1', '菜单管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('88', '87', 'Admin', 'Nav', 'listorders', '', '1', '0', '前台导航排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('89', '87', 'Admin', 'Nav', 'delete', '', '1', '0', '删除菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('90', '87', 'Admin', 'Nav', 'edit', '', '1', '0', '编辑菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('91', '90', 'Admin', 'Nav', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('92', '87', 'Admin', 'Nav', 'add', '', '1', '0', '添加菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('93', '92', 'Admin', 'Nav', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('94', '86', 'Admin', 'Navcat', 'index', '', '1', '1', '菜单分类', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('95', '94', 'Admin', 'Navcat', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('96', '94', 'Admin', 'Navcat', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('97', '96', 'Admin', 'Navcat', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('98', '94', 'Admin', 'Navcat', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('99', '98', 'Admin', 'Navcat', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('100', '85', 'Admin', 'Menu', 'index', '', '1', '1', '后台菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('101', '100', 'Admin', 'Menu', 'add', '', '1', '0', '添加菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('102', '101', 'Admin', 'Menu', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('103', '100', 'Admin', 'Menu', 'listorders', '', '1', '0', '后台菜单排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('104', '100', 'Admin', 'Menu', 'export_menu', '', '1', '0', '菜单备份', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('105', '100', 'Admin', 'Menu', 'edit', '', '1', '0', '编辑菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('106', '105', 'Admin', 'Menu', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('107', '100', 'Admin', 'Menu', 'delete', '', '1', '0', '删除菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('108', '100', 'Admin', 'Menu', 'lists', '', '1', '0', '所有菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('109', '0', 'Admin', 'Setting', 'default', '', '0', '1', '设置', 'cogs', '', '0');
INSERT INTO `cmf_menu` VALUES ('110', '109', 'Admin', 'Setting', 'userdefault', '', '0', '1', '个人信息', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('111', '110', 'Admin', 'User', 'userinfo', '', '1', '1', '修改信息', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('112', '111', 'Admin', 'User', 'userinfo_post', '', '1', '0', '修改信息提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('113', '110', 'Admin', 'Setting', 'password', '', '1', '1', '修改密码', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('114', '113', 'Admin', 'Setting', 'password_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('115', '109', 'Admin', 'Setting', 'site', '', '1', '1', '网站信息', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('116', '115', 'Admin', 'Setting', 'site_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('117', '115', 'Admin', 'Route', 'index', '', '1', '0', '路由列表', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('118', '115', 'Admin', 'Route', 'add', '', '1', '0', '路由添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('119', '118', 'Admin', 'Route', 'add_post', '', '1', '0', '路由添加提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('120', '115', 'Admin', 'Route', 'edit', '', '1', '0', '路由编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('121', '120', 'Admin', 'Route', 'edit_post', '', '1', '0', '路由编辑提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('122', '115', 'Admin', 'Route', 'delete', '', '1', '0', '路由删除', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('123', '115', 'Admin', 'Route', 'ban', '', '1', '0', '路由禁止', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('124', '115', 'Admin', 'Route', 'open', '', '1', '0', '路由启用', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('125', '115', 'Admin', 'Route', 'listorders', '', '1', '0', '路由排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('126', '109', 'Admin', 'Mailer', 'default', '', '1', '1', '邮箱配置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('127', '126', 'Admin', 'Mailer', 'index', '', '1', '1', 'SMTP配置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('128', '127', 'Admin', 'Mailer', 'index_post', '', '1', '0', '提交配置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('129', '126', 'Admin', 'Mailer', 'active', '', '1', '1', '注册邮件模板', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('130', '129', 'Admin', 'Mailer', 'active_post', '', '1', '0', '提交模板', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('131', '109', 'Admin', 'Setting', 'clearcache', '', '1', '1', '清除缓存', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('132', '0', 'User', 'Indexadmin', 'default', '', '1', '1', '用户管理', 'group', '', '40');
INSERT INTO `cmf_menu` VALUES ('133', '132', 'User', 'Indexadmin', 'default1', '', '1', '1', '用户组', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('134', '133', 'User', 'Indexadmin', 'index', '', '1', '1', '本站用户', 'leaf', '', '0');
INSERT INTO `cmf_menu` VALUES ('135', '134', 'User', 'Indexadmin', 'ban', '', '1', '0', '拉黑会员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('136', '134', 'User', 'Indexadmin', 'cancelban', '', '1', '0', '启用会员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('137', '133', 'User', 'Oauthadmin', 'index', '', '1', '1', '第三方用户', 'leaf', '', '0');
INSERT INTO `cmf_menu` VALUES ('138', '137', 'User', 'Oauthadmin', 'delete', '', '1', '0', '第三方用户解绑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('139', '132', 'User', 'Indexadmin', 'default3', '', '1', '1', '管理组', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('140', '139', 'Admin', 'Rbac', 'index', '', '1', '1', '角色管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('141', '140', 'Admin', 'Rbac', 'member', '', '1', '0', '成员管理', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('142', '140', 'Admin', 'Rbac', 'authorize', '', '1', '0', '权限设置', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('143', '142', 'Admin', 'Rbac', 'authorize_post', '', '1', '0', '提交设置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('144', '140', 'Admin', 'Rbac', 'roleedit', '', '1', '0', '编辑角色', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('145', '144', 'Admin', 'Rbac', 'roleedit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('146', '140', 'Admin', 'Rbac', 'roledelete', '', '1', '1', '删除角色', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('147', '140', 'Admin', 'Rbac', 'roleadd', '', '1', '1', '添加角色', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('148', '147', 'Admin', 'Rbac', 'roleadd_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('149', '139', 'Admin', 'User', 'index', '', '1', '1', '管理员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('150', '149', 'Admin', 'User', 'delete', '', '1', '0', '删除管理员', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('151', '149', 'Admin', 'User', 'edit', '', '1', '0', '管理员编辑', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('152', '151', 'Admin', 'User', 'edit_post', '', '1', '0', '编辑提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('153', '149', 'Admin', 'User', 'add', '', '1', '0', '管理员添加', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('154', '153', 'Admin', 'User', 'add_post', '', '1', '0', '添加提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('155', '47', 'Admin', 'Plugin', 'update', '', '1', '0', '插件更新', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('156', '109', 'Admin', 'Storage', 'index', '', '1', '1', '文件存储', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('157', '156', 'Admin', 'Storage', 'setting_post', '', '1', '0', '文件存储设置提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('158', '54', 'Admin', 'Slide', 'ban', '', '1', '0', '禁用幻灯片', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('159', '54', 'Admin', 'Slide', 'cancelban', '', '1', '0', '启用幻灯片', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('160', '149', 'Admin', 'User', 'ban', '', '1', '0', '禁用管理员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('161', '149', 'Admin', 'User', 'cancelban', '', '1', '0', '启用管理员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('166', '127', 'Admin', 'Mailer', 'test', '', '1', '0', '测试邮件', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('167', '109', 'Admin', 'Setting', 'upload', '', '1', '1', '上传设置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('168', '167', 'Admin', 'Setting', 'upload_post', '', '1', '0', '上传设置提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('169', '7', 'Portal', 'AdminPost', 'copy', '', '1', '0', '文章批量复制', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('174', '100', 'Admin', 'Menu', 'backup_menu', '', '1', '0', '备份菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('175', '100', 'Admin', 'Menu', 'export_menu_lang', '', '1', '0', '导出后台菜单多语言包', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('176', '100', 'Admin', 'Menu', 'restore_menu', '', '1', '0', '还原菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('177', '100', 'Admin', 'Menu', 'getactions', '', '1', '0', '导入新菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('193', '0', 'Admin', 'Company', 'index', '', '0', '1', '公司情况管理', '', '', '4');
INSERT INTO `cmf_menu` VALUES ('195', '194', 'Admin', 'House', 'add', '', '1', '0', '添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('194', '193', 'Admin', 'Csituation', 'index', '', '0', '1', '内容管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('196', '0', 'Admin', 'Image', 'index', '', '0', '1', '首页轮播图管理', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('197', '196', 'Admin', 'Image', 'add', '', '1', '0', '添加图片', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('198', '0', 'Admin', 'News', 'index', '', '0', '1', '资讯管理', '', '', '3');
INSERT INTO `cmf_menu` VALUES ('199', '0', 'Admin', 'Service', 'index', '', '0', '1', '服务管理', '', '', '5');
INSERT INTO `cmf_menu` VALUES ('202', '0', 'Admin', 'house', 'index', '', '1', '1', '租房找房管理', '', '', '6');
INSERT INTO `cmf_menu` VALUES ('201', '199', 'Admin', 'Servicecontent', 'index', '', '1', '1', '内容管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('204', '202', 'Admin', 'Housecontent', 'index', '', '1', '1', '内容管理', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('203', '202', 'Admin', 'Houseclass', 'index', '', '1', '1', '分类筛选管理', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('205', '0', 'Admin', 'Transercompany', 'index', '', '1', '1', '资质转让管理', '', '', '7');
INSERT INTO `cmf_menu` VALUES ('206', '205', 'Admin', 'Companyclass', 'index', '', '1', '1', '分类筛选管理', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('207', '205', 'Admin', 'Companycontent', 'index', '', '1', '1', '内容管理', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('208', '0', 'Admin', 'Transfertmall', 'index', '', '1', '1', '天猫转让管理', '', '', '8');
INSERT INTO `cmf_menu` VALUES ('209', '208', 'Admin', 'Tmallclass', 'index', '', '1', '1', '分类筛选管理', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('210', '208', 'Admin', 'Tmallcontent', 'index', '', '1', '1', '内容管理', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('211', '0', 'Admin', 'Coupon', 'index', '', '1', '1', '优惠券管理', '', '', '9');
INSERT INTO `cmf_menu` VALUES ('212', '202', 'Admin', 'Housephone', 'index', '', '1', '1', '客服管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('213', '205', 'Admin', 'Companyphone', 'index', '', '1', '1', '客服管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('214', '208', 'Admin', 'Tmallphone', 'index', '', '1', '1', '客服管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('215', '0', 'Admin', 'Order', 'index', '', '1', '1', '预约订单管理', '', '', '11');
INSERT INTO `cmf_menu` VALUES ('216', '0', 'Admin', 'Servicejd', 'index', '', '1', '1', '服务进度管理', '', '', '10');
INSERT INTO `cmf_menu` VALUES ('217', '0', 'Admin', 'pacter', 'index', '', '1', '1', '订单合同管理', '', '', '12');
INSERT INTO `cmf_menu` VALUES ('218', '0', 'Admin', 'Member', 'index', '', '1', '1', '会员管理', '', '', '13');
INSERT INTO `cmf_menu` VALUES ('219', '0', 'Admin', 'Remark', 'index', '', '1', '1', '需求管理', '', '', '14');
INSERT INTO `cmf_menu` VALUES ('220', '0', 'Admin', 'Memberupload', 'index', '', '1', '1', '上传管理', '', '', '15');
INSERT INTO `cmf_menu` VALUES ('221', '0', 'Admin', 'withdraw', 'index', '', '1', '1', '提现订单管理', '', '', '18');
INSERT INTO `cmf_menu` VALUES ('222', '0', 'Admin', 'Integral', 'index', '', '1', '1', '服务积分管理', '', '', '19');
INSERT INTO `cmf_menu` VALUES ('223', '0', 'Admin', 'Fiscal', 'Index', '', '1', '1', '财税管理', '', '', '20');
INSERT INTO `cmf_menu` VALUES ('224', '217', 'Admin', 'Pactoption', 'index', '', '1', '1', '合同项管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('225', '217', 'Admin', 'Pacttemp', 'index', '', '1', '1', '合同模板管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('226', '217', 'Admin', 'Pact', 'index', '', '1', '1', '订单合同管理', '', '', '0');

-- ----------------------------
-- Table structure for cmf_nav
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nav`;
CREATE TABLE `cmf_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '导航分类 id',
  `parentid` int(11) NOT NULL COMMENT '导航父 id',
  `label` varchar(255) NOT NULL COMMENT '导航标题',
  `target` varchar(50) DEFAULT NULL COMMENT '打开方式',
  `href` varchar(255) NOT NULL COMMENT '导航链接',
  `icon` varchar(255) NOT NULL COMMENT '导航图标',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `listorder` int(6) DEFAULT '0' COMMENT '排序',
  `path` varchar(255) NOT NULL DEFAULT '0' COMMENT '层级关系',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='前台导航表';

-- ----------------------------
-- Records of cmf_nav
-- ----------------------------
INSERT INTO `cmf_nav` VALUES ('1', '1', '0', '首页', '', 'home', '', '1', '0', '0-1');
INSERT INTO `cmf_nav` VALUES ('2', '1', '0', '列表演示', '', 'a:2:{s:6:\"action\";s:17:\"Portal/List/index\";s:5:\"param\";a:1:{s:2:\"id\";s:1:\"1\";}}', '', '1', '0', '0-2');
INSERT INTO `cmf_nav` VALUES ('3', '1', '0', '瀑布流', '', 'a:2:{s:6:\"action\";s:17:\"Portal/List/index\";s:5:\"param\";a:1:{s:2:\"id\";s:1:\"2\";}}', '', '1', '0', '0-3');

-- ----------------------------
-- Table structure for cmf_nav_cat
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nav_cat`;
CREATE TABLE `cmf_nav_cat` (
  `navcid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '导航分类名',
  `active` int(1) NOT NULL DEFAULT '1' COMMENT '是否为主菜单，1是，0不是',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`navcid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='前台导航分类表';

-- ----------------------------
-- Records of cmf_nav_cat
-- ----------------------------
INSERT INTO `cmf_nav_cat` VALUES ('1', '主导航', '1', '主导航');

-- ----------------------------
-- Table structure for cmf_news
-- ----------------------------
DROP TABLE IF EXISTS `cmf_news`;
CREATE TABLE `cmf_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `picture` varchar(255) DEFAULT NULL COMMENT '图片',
  `content` text COMMENT '内容',
  `click` int(11) DEFAULT '0' COMMENT '浏览次数',
  `sort` int(10) DEFAULT '0',
  `status` tinyint(2) DEFAULT '1' COMMENT '1显示 2下架',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='资讯表';

-- ----------------------------
-- Records of cmf_news
-- ----------------------------
INSERT INTO `cmf_news` VALUES ('14', '高新企业认定可以为公司获得的6点', '20180726\\5520b5980f095945661bed6cf2effc43.png', '<p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">以下这些是外商投资中最普通最常见的外资注册问题，如果这些前期准备没有做好，可能会对公司的运作造成潜在的不利影响。目前，部分个人外国投资者为了节省代理注册公司的代理费，认为注册公司机构只是简单的跑跑腿，不应该收取咨询代理费用。因此，有不少人会在没有前期充分合理的规划下，直接租赁办公场地，委托自己公司的员工从网上下载办事程序自行办理。这种看是节省，实则盲目的方式使得新注册外资的公司可能失去可以享受的相关规定，致使造成今后不必要的损失。</p><h4 style=\"white-space: normal; margin: 0px; padding: 0px; font-weight: normal; font-size: 14px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">一、注册外资公司的名称<br style=\"padding: 0px; margin: 0px;\"/></h4><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">&nbsp; 注册一个外资公司只需要登记注册中文名称，然而客户在国外使用的都是英文或者其他文字的名称，如何将国外的外文名称与在中国注册的中文名称有机结合起来显得十分重要。</p><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\"><br/></p><h4 style=\"white-space: normal; margin: 0px; padding: 0px; font-weight: normal; font-size: 14px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">二、注册外资<a href=\"http://www.yikesong66.com/index.php?g=&m=service&a=index&id=31\" target=\"_self\" style=\"color: rgb(85, 85, 85); font-size: 18px;\">公司地址</a></h4><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">&nbsp; 注册地址选择的好坏不仅直接涉及到企业可以享受的<a href=\"http://www.yikesong66.com/index.php?g=&m=service&a=index&id=111\" target=\"_self\" style=\"color: rgb(85, 85, 85); font-size: 18px;\">财政扶持政策</a>和优惠政策外，更为重要的是关系到企业的长期发展战略。不过庆幸的是，现在也不乏专业的外资注册公司，他们不仅为客户提供一手的注册公司相关政策法规，分析注册外资公司的优劣势，结合客户的需求制定最优的方案，以便客户可以取得长期的发展。</p><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\"><br/></p><h4 style=\"white-space: normal; margin: 0px; padding: 0px; font-weight: normal; font-size: 14px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">三、注册外资公司资本</h4><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">&nbsp; 对于外资公司注册资本的问题，小编想说的是不能一味在意注册资本。在很多客商在咨询初期，都很关心我国规定的注册资本。然而随着国家相关法规的改革，注册资金由以往的实缴制改为认缴制，对于注册资金的要求大大降低，但在实践过程中，注册资金过高可能会引起有关部门的严查，但倘诺注册资本太低，则不能获得审批部门通过，即使通过了，今后在银行开户、企业运作中也会遇到很多问题，导致最终不得不增资或者追加外债。</p><p><br/></p>', '105', '1', '1', '2017-06-22 17:12:04', '2018-09-06 20:26:15');
INSERT INTO `cmf_news` VALUES ('15', '注册外资公司的3个重点', '20180726\\e8c950c364401469e5836d2aff8008d5.png', '<p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">以下这些是外商投资中最普通最常见的外资注册问题，如果这些前期准备没有做好，可能会对公司的运作造成潜在的不利影响。目前，部分个人外国投资者为了节省代理注册公司的代理费，认为注册公司机构只是简单的跑跑腿，不应该收取咨询代理费用。因此，有不少人会在没有前期充分合理的规划下，直接租赁办公场地，委托自己公司的员工从网上下载办事程序自行办理。这种看是节省，实则盲目的方式使得新注册外资的公司可能失去可以享受的相关规定，致使造成今后不必要的损失。</p><h4 style=\"white-space: normal; margin: 0px; padding: 0px; font-weight: normal; font-size: 14px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">一、注册外资公司的名称<br style=\"padding: 0px; margin: 0px;\"/></h4><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">&nbsp; 注册一个外资公司只需要登记注册中文名称，然而客户在国外使用的都是英文或者其他文字的名称，如何将国外的外文名称与在中国注册的中文名称有机结合起来显得十分重要。</p><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\"><br/></p><h4 style=\"white-space: normal; margin: 0px; padding: 0px; font-weight: normal; font-size: 14px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">二、注册外资<a href=\"http://www.yikesong66.com/index.php?g=&m=service&a=index&id=31\" target=\"_self\" style=\"color: rgb(85, 85, 85); font-size: 18px;\">公司地址</a></h4><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">&nbsp; 注册地址选择的好坏不仅直接涉及到企业可以享受的<a href=\"http://www.yikesong66.com/index.php?g=&m=service&a=index&id=111\" target=\"_self\" style=\"color: rgb(85, 85, 85); font-size: 18px;\">财政扶持政策</a>和优惠政策外，更为重要的是关系到企业的长期发展战略。不过庆幸的是，现在也不乏专业的外资注册公司，他们不仅为客户提供一手的注册公司相关政策法规，分析注册外资公司的优劣势，结合客户的需求制定最优的方案，以便客户可以取得长期的发展。</p><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\"><br/></p><h4 style=\"white-space: normal; margin: 0px; padding: 0px; font-weight: normal; font-size: 14px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">三、注册外资公司资本</h4><p style=\"margin-top: 0px; margin-bottom: 15px; white-space: normal; padding: 0px; text-indent: 2em; line-height: 21px; color: rgb(85, 85, 85); font-family: Arial, 微软雅黑, 黑体, sans-serif; font-size: 14px; text-align: -webkit-center; background-color: rgb(255, 255, 255);\">&nbsp; 对于外资公司注册资本的问题，小编想说的是不能一味在意注册资本。在很多客商在咨询初期，都很关心我国规定的注册资本。然而随着国家相关法规的改革，注册资金由以往的实缴制改为认缴制，对于注册资金的要求大大降低，但在实践过程中，注册资金过高可能会引起有关部门的严查，但倘诺注册资本太低，则不能获得审批部门通过，即使通过了，今后在银行开户、企业运作中也会遇到很多问题，导致最终不得不增资或者追加外债。</p><p><br/></p>', '114', '2', '1', '2017-06-22 17:12:39', '2018-09-06 20:26:15');

-- ----------------------------
-- Table structure for cmf_notice
-- ----------------------------
DROP TABLE IF EXISTS `cmf_notice`;
CREATE TABLE `cmf_notice` (
  `notice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(250) DEFAULT '' COMMENT '公告内容',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`notice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_notice
-- ----------------------------
INSERT INTO `cmf_notice` VALUES ('1', '更丰富或或或或或或或或或', '2018-09-06 20:10:57');

-- ----------------------------
-- Table structure for cmf_oauth_user
-- ----------------------------
DROP TABLE IF EXISTS `cmf_oauth_user`;
CREATE TABLE `cmf_oauth_user` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `from` varchar(20) NOT NULL COMMENT '用户来源key',
  `name` varchar(30) NOT NULL COMMENT '第三方昵称',
  `head_img` varchar(200) NOT NULL COMMENT '头像',
  `uid` int(20) NOT NULL COMMENT '关联的本站用户id',
  `create_time` datetime NOT NULL COMMENT '绑定时间',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(16) NOT NULL COMMENT '最后登录ip',
  `login_times` int(6) NOT NULL COMMENT '登录次数',
  `status` tinyint(2) NOT NULL,
  `access_token` varchar(512) NOT NULL,
  `expires_date` int(11) NOT NULL COMMENT 'access_token过期时间',
  `openid` varchar(40) NOT NULL COMMENT '第三方用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方用户表';

-- ----------------------------
-- Records of cmf_oauth_user
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_options
-- ----------------------------
DROP TABLE IF EXISTS `cmf_options`;
CREATE TABLE `cmf_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL COMMENT '配置名',
  `option_value` longtext NOT NULL COMMENT '配置值',
  `autoload` int(2) NOT NULL DEFAULT '1' COMMENT '是否自动加载',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='全站配置表';

-- ----------------------------
-- Records of cmf_options
-- ----------------------------
INSERT INTO `cmf_options` VALUES ('1', 'member_email_active', '{\"title\":\"ThinkCMF\\u90ae\\u4ef6\\u6fc0\\u6d3b\\u901a\\u77e5.\",\"template\":\"<p>\\u672c\\u90ae\\u4ef6\\u6765\\u81ea<a href=\\\"http:\\/\\/www.thinkcmf.com\\\">ThinkCMF<\\/a><br\\/><br\\/>&nbsp; &nbsp;<strong>---------------<strong style=\\\"white-space: normal;\\\">---<\\/strong><\\/strong><br\\/>&nbsp; &nbsp;<strong>\\u5e10\\u53f7\\u6fc0\\u6d3b\\u8bf4\\u660e<\\/strong><br\\/>&nbsp; &nbsp;<strong>---------------<strong style=\\\"white-space: normal;\\\">---<\\/strong><\\/strong><br\\/><br\\/>&nbsp; &nbsp; \\u5c0a\\u656c\\u7684<span style=\\\"FONT-SIZE: 16px; FONT-FAMILY: Arial; COLOR: rgb(51,51,51); LINE-HEIGHT: 18px; BACKGROUND-COLOR: rgb(255,255,255)\\\">#username#\\uff0c\\u60a8\\u597d\\u3002<\\/span>\\u5982\\u679c\\u60a8\\u662fThinkCMF\\u7684\\u65b0\\u7528\\u6237\\uff0c\\u6216\\u5728\\u4fee\\u6539\\u60a8\\u7684\\u6ce8\\u518cEmail\\u65f6\\u4f7f\\u7528\\u4e86\\u672c\\u5730\\u5740\\uff0c\\u6211\\u4eec\\u9700\\u8981\\u5bf9\\u60a8\\u7684\\u5730\\u5740\\u6709\\u6548\\u6027\\u8fdb\\u884c\\u9a8c\\u8bc1\\u4ee5\\u907f\\u514d\\u5783\\u573e\\u90ae\\u4ef6\\u6216\\u5730\\u5740\\u88ab\\u6ee5\\u7528\\u3002<br\\/>&nbsp; &nbsp; \\u60a8\\u53ea\\u9700\\u70b9\\u51fb\\u4e0b\\u9762\\u7684\\u94fe\\u63a5\\u5373\\u53ef\\u6fc0\\u6d3b\\u60a8\\u7684\\u5e10\\u53f7\\uff1a<br\\/>&nbsp; &nbsp; <a title=\\\"\\\" href=\\\"http:\\/\\/#link#\\\" target=\\\"_self\\\">http:\\/\\/#link#<\\/a><br\\/>&nbsp; &nbsp; (\\u5982\\u679c\\u4e0a\\u9762\\u4e0d\\u662f\\u94fe\\u63a5\\u5f62\\u5f0f\\uff0c\\u8bf7\\u5c06\\u8be5\\u5730\\u5740\\u624b\\u5de5\\u7c98\\u8d34\\u5230\\u6d4f\\u89c8\\u5668\\u5730\\u5740\\u680f\\u518d\\u8bbf\\u95ee)<br\\/>&nbsp; &nbsp; \\u611f\\u8c22\\u60a8\\u7684\\u8bbf\\u95ee\\uff0c\\u795d\\u60a8\\u4f7f\\u7528\\u6109\\u5feb\\uff01<br\\/><br\\/>&nbsp; &nbsp; \\u6b64\\u81f4<br\\/>&nbsp; &nbsp; ThinkCMF \\u7ba1\\u7406\\u56e2\\u961f.<\\/p>\"}', '1');
INSERT INTO `cmf_options` VALUES ('6', 'site_options', '{\"site_name\":\"\\u4e00\\u68f5\\u677e\",\"site_admin_url_password\":\"\",\"site_tpl\":\"simplebootx\",\"mobile_tpl_enabled\":\"1\",\"site_adminstyle\":\"flat\",\"site_icp\":\"\",\"site_admin_email\":\"2168350480@qq.com\",\"site_tongji\":\"\",\"site_copyright\":\"\",\"site_seo_title\":\"\\u4e00\\u68f5\\u677e\",\"site_seo_keywords\":\"\\u4e00\\u68f5\\u677e\",\"site_seo_description\":\"\\u4e00\\u68f5\\u677e\",\"urlmode\":\"0\",\"html_suffix\":\"\",\"comment_time_interval\":\"60\"}', '1');
INSERT INTO `cmf_options` VALUES ('7', 'cmf_settings', '{\"banned_usernames\":\"\"}', '1');
INSERT INTO `cmf_options` VALUES ('8', 'cdn_settings', '{\"cdn_static_root\":\"\"}', '1');

-- ----------------------------
-- Table structure for cmf_order
-- ----------------------------
DROP TABLE IF EXISTS `cmf_order`;
CREATE TABLE `cmf_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordernum` char(30) NOT NULL COMMENT '订单号',
  `uid` int(11) NOT NULL COMMENT '用户表ID',
  `url` text COMMENT '后台查看详情链接地址',
  `service_name` char(80) DEFAULT NULL,
  `mod` char(50) NOT NULL COMMENT '所在服务表名',
  `service_id` int(11) NOT NULL COMMENT '服务内容ID',
  `sprice` int(11) DEFAULT NULL COMMENT '所属服务底价',
  `price` double(11,2) DEFAULT '0.00' COMMENT '价格',
  `pay_money` double(11,2) DEFAULT '0.00' COMMENT '支付价格',
  `content` text COMMENT '用户选择的选项',
  `product_log` mediumtext COMMENT '产品信息',
  `liable` char(20) DEFAULT NULL COMMENT '订单负责人',
  `remark` text COMMENT '非正常订单备注信息',
  `company_name` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `corporation` char(20) DEFAULT NULL COMMENT '法人',
  `mtc` tinyint(1) DEFAULT NULL COMMENT '1季付2半年付3年付',
  `belong_id` int(10) DEFAULT '0' COMMENT '所属订单id,仅对于财税管理订单催缴生成的新订单',
  `debt` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否催账状态 1不是 2是',
  `pact` tinyint(1) DEFAULT '1' COMMENT '合同状态 1未填写 2已填写 3客户已签订',
  `assign` tinyint(1) DEFAULT '1' COMMENT '分配情况 1待分配 2待接单 3已接单',
  `status` tinyint(1) DEFAULT '1' COMMENT '订单状态，1等待输入价格 2等待支付 3已支付，处理中 4已完成 ',
  `debt_time` datetime DEFAULT NULL COMMENT '工期',
  `nextpay_time` datetime DEFAULT NULL COMMENT '需下次支付时间',
  `use_coupon` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否使用优惠券 1使用 2未使用',
  `member_coupon_id` int(11) DEFAULT NULL COMMENT '用户优惠券表ID',
  `audit` tinyint(1) DEFAULT '1' COMMENT '0 非正常 1正常',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否删除 0否 1是',
  `pay_time` datetime(1) DEFAULT NULL COMMENT '支付时间',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`,`use_coupon`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_order
-- ----------------------------
INSERT INTO `cmf_order` VALUES ('34', '15039132689061', '58', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.01', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', '', '大河', '大河', null, '0', '1', '1', '2', '5', null, null, '2', '0', '1', '0', null, '2017-08-28 17:41:08', '2018-09-07 16:59:15');
INSERT INTO `cmf_order` VALUES ('35', '15039691557886', '58', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '1600.00', '0.00', '注册地址:杭州注册,注册区域:拱墅区,注册资金:1-100万,是否记账:是,税务种类:一般纳税人,有无地址:无,优质公司:投资预估大的企业', null, '雷全', '123456', null, null, null, '0', '1', '2', '2', '5', null, null, '2', '0', '1', '0', null, '2017-08-29 09:12:35', '2018-09-07 16:56:11');
INSERT INTO `cmf_order` VALUES ('36', '15039835165826', '58', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.01', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', null, '大河', '大河', '1', '0', '2', '1', '2', '5', '2017-08-29 14:07:36', '2017-07-29 14:07:36', '2', null, '1', '0', null, '2017-08-29 13:11:56', '2018-09-07 16:59:35');
INSERT INTO `cmf_order` VALUES ('40', '15039873745335', '58', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.01', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', null, null, null, '1', '0', '1', '1', '2', '5', '2017-08-30 12:37:05', '2017-11-30 12:37:05', '2', null, '1', '0', null, '2017-08-29 14:16:14', '2018-09-07 17:02:48');
INSERT INTO `cmf_order` VALUES ('41', '15048620889608', '20', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '3000.00', '0.00', '税务种类:小规模纳税人,付款方式:半年付', null, '雷全', null, null, null, '3', '0', '1', '1', '2', '5', '2017-09-08 17:19:32', '2018-09-08 17:19:32', '2', null, '1', '0', null, '2017-09-08 17:14:48', '2018-09-07 17:06:29');
INSERT INTO `cmf_order` VALUES ('42', '15048632780549', '20', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:年付（8.3）折', null, '雷全', null, 'ceshi', 'ceshi', null, '0', '1', '2', '2', '5', null, null, '2', null, '1', '0', null, '2017-09-08 17:34:38', '2018-09-07 17:10:08');
INSERT INTO `cmf_order` VALUES ('43', '15048654260883', '17', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=增资验资', '验资审计', 'servicecontent', '4', '800', '1600.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, '雷全', null, null, null, null, '0', '1', '2', '2', '5', null, null, '2', null, '1', '0', null, '2017-09-08 18:10:26', '2018-09-07 17:11:10');
INSERT INTO `cmf_order` VALUES ('44', '15051207768395', '17', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:年付（8.3）折', null, '老吴', null, '杭州风步信息技术有限公司', '吴明印', null, '0', '1', '2', '1', '5', null, null, '2', null, '1', '0', null, '2017-09-11 17:06:16', null);
INSERT INTO `cmf_order` VALUES ('45', '15051212332051', '17', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '3900.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', '就是不对', '杭州风步信息技术有限公司', '吴明印', '3', '0', '1', '2', '2', '6', '2017-09-11 17:28:35', '2018-09-11 17:28:35', '2', null, '1', '0', null, '2017-09-11 17:13:53', '2018-09-10 09:42:45');
INSERT INTO `cmf_order` VALUES ('46', '15051212525983', '96', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '3000.00', '0.00', '税务种类:小规模纳税人,付款方式:年付（8.3）折', null, '雷全', null, '风步', '吴', '3', '0', '1', '2', '2', '5', '2017-09-11 17:16:37', '2018-09-11 17:16:37', '2', null, '1', '0', null, '2017-09-11 17:14:12', '2018-09-10 09:52:41');
INSERT INTO `cmf_order` VALUES ('47', '15051224018416', '17', 'index.php?g=&amp;m=index&amp;a=special_services_detail&amp;name=工商变更', '工商变更', 'servicecontent', '6', '800', '0.00', '0.00', '变更内容:公司名称,增资减资', null, '122', null, null, null, null, '0', '1', '2', '1', '5', null, null, '2', null, '1', '0', null, '2017-09-11 17:33:21', null);
INSERT INTO `cmf_order` VALUES ('48', '15051229029554', '17', 'index.php?g=&amp;m=index&amp;a=special_services_detail&amp;name=工商变更', '工商变更', 'servicecontent', '6', '800', '0.00', '0.00', '变更内容:公司名称,股东变更', null, '雷全', null, null, null, null, '0', '1', '2', '2', '5', null, null, '2', null, '1', '0', null, '2017-09-11 17:41:42', '2018-09-10 09:56:13');
INSERT INTO `cmf_order` VALUES ('49', '15051253093333', '17', 'index.php?g=&amp;m=index&amp;a=special_services_detail&amp;name=工商变更', '工商变更', 'servicecontent', '6', '800', '800.00', '0.00', '变更内容:法人变更,增资减资', null, null, null, null, null, null, '0', '1', '2', '1', '6', null, null, '2', null, '1', '0', null, '2017-09-11 18:21:49', '2017-09-11 18:22:55');
INSERT INTO `cmf_order` VALUES ('50', '15051254471906', '17', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.01', '0.00', '税务种类:小规模纳税人,付款方式:年付（8.3）折', null, null, null, null, null, '3', '0', '1', '2', '1', '6', '2017-09-11 18:24:29', '2018-09-11 18:24:29', '2', null, '1', '0', null, '2017-09-11 18:24:07', '2017-09-11 18:24:29');
INSERT INTO `cmf_order` VALUES ('51', '15051262247810', '17', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '0.01', '0.00', '注册地址:杭州注册,注册区域:下城区,注册资金:1000万以上,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, null, '1klsdajflkj', null, null, null, '0', '1', '2', '1', '6', null, null, '2', null, '1', '0', null, '2017-09-11 18:37:04', '2017-09-11 18:38:26');
INSERT INTO `cmf_order` VALUES ('52', '15087530934969', '20', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '0.01', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, null, null, null, null, null, '0', '1', '2', '1', '6', null, null, '2', null, '1', '0', null, '2017-10-23 18:04:53', '2017-10-23 18:05:13');
INSERT INTO `cmf_order` VALUES ('53', '15087559209341', '20', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '0.01', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, null, null, null, null, null, '0', '1', '2', '1', '3', null, null, '2', null, '1', '0', null, '2017-10-23 18:52:00', '2017-10-23 18:52:21');
INSERT INTO `cmf_order` VALUES ('54', '15087560095153', '20', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '45.00', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, null, null, null, null, null, '0', '1', '1', '1', '2', null, null, '2', null, '1', '0', null, '2017-10-23 18:53:29', '2018-04-04 22:02:43');
INSERT INTO `cmf_order` VALUES ('55', '15094271593369', '11', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '0.01', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, null, null, null, null, null, '0', '1', '1', '1', '6', null, null, '2', null, '1', '0', null, '2017-10-31 13:19:19', '2017-10-31 13:20:42');
INSERT INTO `cmf_order` VALUES ('56', '15123038292564', '11', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=法律咨询', '法律咨询', 'servicecontent', '10', '800', '0.00', '0.00', '法律服务:债权债务', null, null, '订单', null, null, null, '0', '1', '1', '1', '6', null, null, '2', null, '1', '0', null, '2017-12-03 20:23:49', null);
INSERT INTO `cmf_order` VALUES ('57', '15132387413075', '11', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '0.00', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, null, '发发发', null, null, null, '0', '1', '1', '1', '6', null, null, '2', null, '1', '0', null, '2017-12-14 16:05:41', null);
INSERT INTO `cmf_order` VALUES ('58', '15142704762575', '11', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '0.00', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, null, '价格不对', null, null, null, '0', '1', '1', '1', '6', null, null, '2', null, '1', '0', null, '2017-12-26 14:41:16', null);
INSERT INTO `cmf_order` VALUES ('59', '15151440835878', '11', 'index.php?g=&amp;m=index&amp;a=house_detail&amp;id=1', '租房找房', 'housecontent', '1', '800', '0.00', '0.00', null, null, null, '价格不对', null, null, null, '0', '1', '1', '1', '6', null, null, '2', null, '1', '0', null, '2018-01-05 17:21:23', null);
INSERT INTO `cmf_order` VALUES ('60', '15194603418605', '11', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '1.00', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, '雷全', 'ffff', null, null, null, '0', '1', '2', '2', '3', null, null, '1', null, '0', '0', null, '2018-02-24 16:19:01', '2018-09-10 10:34:05');
INSERT INTO `cmf_order` VALUES ('61', '15194612982277', '11', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '1.00', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, 'hhgge', 'fffff', null, null, null, '0', '1', '2', '1', '4', null, null, '1', null, '0', '0', null, '2018-02-24 16:34:58', '2018-08-27 10:27:47');
INSERT INTO `cmf_order` VALUES ('62', '15201446070579', '99', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '0.01', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, 'adminl', 'ggg', null, null, null, '0', '1', '2', '1', '6', null, null, '1', null, '1', '0', null, '2018-03-04 14:23:27', '2018-03-04 14:24:00');
INSERT INTO `cmf_order` VALUES ('63', '15220421177463', '98', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, null, '', '杭州风步科技', '吴', '1', '0', '1', '1', '1', '2', null, null, '2', null, '1', '0', null, '2018-03-26 13:28:37', '2018-09-06 10:07:09');
INSERT INTO `cmf_order` VALUES ('64', '15220476465619', '98', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=APP制作', 'APP制作', 'servicecontent', '9', '800', '1500.00', '0.00', '官网定制:手机网站,app制作:app开发,微信开发:微信公众号开发', null, null, null, null, null, null, '0', '1', '1', '1', '2', null, null, '2', null, '1', '0', null, '2018-03-26 15:00:46', '2018-03-28 17:57:31');
INSERT INTO `cmf_order` VALUES ('65', '15220476587114', '98', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=APP制作', 'APP制作', 'servicecontent', '9', '800', '1500.00', '0.00', '官网定制:手机网站,app制作:app开发,微信开发:微信公众号开发', null, null, null, null, null, null, '0', '1', '1', '1', '5', null, null, '2', null, '1', '0', null, '2018-03-26 15:00:58', '2018-08-06 16:50:50');
INSERT INTO `cmf_order` VALUES ('66', '15220477093886', '98', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, null, null, null, null, null, '0', '1', '1', '1', '1', null, null, '2', null, '1', '0', null, '2018-03-26 15:01:49', null);
INSERT INTO `cmf_order` VALUES ('67', '15220484299654', '98', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '333.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, null, null, null, null, '1', '0', '1', '1', '1', '5', '2018-04-26 18:43:04', '2018-07-26 18:43:04', '2', null, '1', '0', null, '2018-03-26 15:13:49', '2018-08-08 18:09:38');
INSERT INTO `cmf_order` VALUES ('68', '15220488945723', '98', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=APP制作', 'APP制作', 'servicecontent', '9', '800', '1500.00', '0.00', '官网定制:模板网站,app制作:app开发,微信开发:微信公众号开发', null, null, null, null, null, null, '0', '1', '1', '1', '5', null, null, '2', null, '1', '0', null, '2018-03-26 15:21:34', '2018-08-06 16:41:16');
INSERT INTO `cmf_order` VALUES ('69', '15220492621988', '98', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=增资验资', '验资审计', 'servicecontent', '4', '800', '1000.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, null, 'hhhh', null, null, null, '0', '1', '2', '1', '2', null, null, '2', null, '1', '0', null, '2018-03-26 15:27:42', '2018-08-02 18:02:45');
INSERT INTO `cmf_order` VALUES ('70', '15221192442754', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=增资验资', '验资审计', 'servicecontent', '4', '800', '1000.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, '非', null, null, null, null, '0', '1', '2', '1', '2', null, null, '2', null, '1', '0', null, '2018-03-27 10:54:04', '2018-08-16 16:19:55');
INSERT INTO `cmf_order` VALUES ('71', '15221264085238', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, null, null, null, null, '1', '0', '1', '1', '1', '2', '2018-03-28 09:43:59', '2018-06-28 09:43:59', '2', null, '1', '0', null, '2018-03-27 12:53:28', '2018-03-28 09:43:59');
INSERT INTO `cmf_order` VALUES ('72', '15221282301198', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=APP制作', 'APP制作', 'servicecontent', '9', '800', '100.00', '0.00', '官网定制:模板网站,app制作:app开发,微信开发:微信公众号开发', null, '方法', null, null, null, null, '0', '1', '2', '1', '2', null, null, '2', null, '1', '0', null, '2018-03-27 13:23:50', '2018-03-28 10:58:32');
INSERT INTO `cmf_order` VALUES ('73', '15221282421842', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=政策规划', '政策规划', 'servicecontent', '7', '800', '1000.00', '0.00', '缴税情况:100万以上,专利项目:实用型,公司区域:上城区,高新人才:有,创业扶持:大学生,股东属地:杭州本地', null, 'fff', null, null, null, null, '0', '1', '1', '1', '3', null, null, '2', null, '1', '0', null, '2018-03-27 13:24:02', '2018-08-06 14:13:42');
INSERT INTO `cmf_order` VALUES ('74', '15222144577025', '99', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '1600.00', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, '雷全', null, null, null, null, '0', '1', '1', '1', '3', null, null, '2', null, '1', '0', null, '2018-03-28 13:20:57', '2018-03-28 13:21:55');
INSERT INTO `cmf_order` VALUES ('75', '15224769544786', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '200.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', '订单出错了', '杭州风步科技', '吴先生', null, '0', '2', '1', '1', '4', null, '2018-03-31 14:15:54', '2', null, '1', '0', null, '2018-03-31 14:15:54', '2018-09-10 15:55:52');
INSERT INTO `cmf_order` VALUES ('76', '15224811281651', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=增资验资', '验资审计', 'servicecontent', '4', '800', '100.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, 'yyyy', 'nnnn', null, null, null, '0', '1', '2', '1', '2', null, null, '2', null, '1', '0', null, '2018-03-31 15:25:28', '2018-03-31 16:58:47');
INSERT INTO `cmf_order` VALUES ('77', '15226598526920', '99', 'index.php?g=&amp;m=index&amp;a=regcompany', '公司注册', 'servicecontent', '5', '800', '1000.00', '0.00', '注册地址:杭州注册,注册区域:上城区,注册资金:100-500万,是否记账:是,税务种类:小规模纳税人,有无地址:有,优质公司:一般企业注册', null, '雷全', null, null, null, null, '0', '1', '1', '1', '4', null, null, '2', null, '1', '0', null, '2018-04-02 17:04:12', '2018-08-07 17:04:33');
INSERT INTO `cmf_order` VALUES ('78', '15227363115166', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', '订单出错了', '杭州风步科技', '吴先生', null, '0', '2', '2', '1', '4', null, '2018-03-31 14:15:54', '2', null, '1', '0', null, '2018-04-03 14:18:31', '2018-08-08 17:58:16');
INSERT INTO `cmf_order` VALUES ('79', '15227366925423', '99', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=财税管理', '财税管理', 'servicecontent', '8', '800', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, 'admin', '订单出错了', '杭州风步科技', '吴先生', null, '0', '1', '2', '1', '4', null, '2018-03-31 14:15:54', '2', null, '1', '0', null, '2018-04-03 14:24:52', null);
INSERT INTO `cmf_order` VALUES ('80', '15318180797991', '100', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=增资验资', '验资审计', 'servicecontent', '4', '800', '0.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, 'feic', null, null, null, null, '0', '1', '1', '1', '1', null, null, '2', null, '1', '0', null, '2018-07-17 17:01:19', '2018-08-06 14:07:30');
INSERT INTO `cmf_order` VALUES ('81', '15318181088232', '100', 'index.php?g=&amp;m=index&amp;a=services_detail&amp;name=政策规划', '政策规划', 'servicecontent', '7', '800', '100.00', '0.00', '缴税情况:100万以上,专利项目:实用型,公司区域:上城区,高新人才:有,创业扶持:大学生,股东属地:杭州本地', null, '雷', '错错错', null, null, null, '0', '1', '2', '1', '3', null, null, '2', null, '0', '0', null, '2018-07-17 17:01:48', '2018-08-16 16:36:39');
INSERT INTO `cmf_order` VALUES ('82', '15337222957928', '99', null, '财税管理', 'servicecontent', '8', '800', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', null, '杭州风步科技', null, null, '0', '1', '1', '1', '2', null, null, '2', null, '1', '0', null, '2018-08-08 17:58:15', '2018-08-08 17:58:15');
INSERT INTO `cmf_order` VALUES ('83', '15347536412352', '123', null, '园区服务', 'Housecontent', '1', null, '600.00', '0.00', '\'\'', null, '雷全', null, null, null, null, '0', '1', '1', '1', '2', null, null, '2', null, '1', '0', null, '2018-08-20 16:27:21', '2018-08-20 16:27:21');
INSERT INTO `cmf_order` VALUES ('84', '15347539796843', '123', null, '验资审计', 'Servicecontent', '4', null, '1000.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, '雷全', '具有最高权限', null, null, null, '0', '1', '1', '1', '2', null, null, '2', null, '0', '0', null, '2018-08-20 16:32:59', '2018-08-23 09:39:20');
INSERT INTO `cmf_order` VALUES ('85', '15359454845795', '126', null, '验资审计', 'Servicecontent', '5', '1600', '1000.00', '1000.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, '雷全', null, null, null, null, '0', '1', '2', '1', '3', null, null, '2', null, '1', '0', null, '2018-09-03 11:31:24', '2018-09-10 15:39:47');
INSERT INTO `cmf_order` VALUES ('86', '15359458083844', '126', null, '验资审计', 'Servicecontent', '4', '1600', '2000.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, '雷全', null, null, null, null, '0', '1', '1', '1', '2', null, null, '2', null, '1', '0', null, '2018-09-03 11:36:48', '2018-09-10 16:48:02');
INSERT INTO `cmf_order` VALUES ('87', '15359516513999', '126', null, '验资审计', 'Servicecontent', '4', '1600', '800.00', '0.00', '增资金额:3000-10000万,验资报告:要,是否新设:是', null, '雷全', null, null, null, null, '0', '1', '1', '1', '1', null, null, '2', null, '1', '0', null, '2018-09-03 13:14:11', '2018-09-03 13:21:52');
INSERT INTO `cmf_order` VALUES ('88', '15359517404279', '126', null, '财税管理', 'Servicecontent', '8', '200', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', null, null, null, null, '0', '1', '1', '1', '1', null, null, '2', null, '1', '0', null, '2018-09-03 13:15:40', '2018-09-03 13:15:40');
INSERT INTO `cmf_order` VALUES ('89', '15359517443125', '126', null, '财税管理', 'Servicecontent', '8', '200', '1000.00', '500.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '0', '1', '2', '3', '4', null, '2018-10-05 16:30:47', '2', null, '1', '1', null, '2018-09-03 13:15:44', '2018-09-10 18:19:37');
INSERT INTO `cmf_order` VALUES ('90', '15359517828828', '126', null, 'APP制作', 'Servicecontent', '9', '1500', '0.00', '0.00', '官网定制:模板网站,app制作:app开发,微信开发:微信公众号开发', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', '订单出错啦', null, null, null, '0', '1', '1', '3', '1', null, null, '2', null, '0', '0', null, '2018-09-03 13:16:22', '2018-09-05 13:25:58');
INSERT INTO `cmf_order` VALUES ('91', '15359517837732', '126', null, 'APP制作', 'Servicecontent', '9', '1500', '8000.00', '0.00', '官网定制:模板网站,app制作:app开发,微信开发:微信公众号开发', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', '订单出错啦', null, null, null, '0', '1', '2', '1', '1', null, null, '2', null, '0', '0', null, '2018-09-03 13:16:23', '2018-09-06 10:24:31');
INSERT INTO `cmf_order` VALUES ('92', '15359519236655', '126', null, '园区服务', 'Housecontent', '1', '1200', '0.00', '0.00', '', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, null, '0', '1', '1', '3', '1', null, null, '2', null, '1', '0', null, '2018-09-03 13:18:43', '2018-09-03 13:18:43');
INSERT INTO `cmf_order` VALUES ('93', '15359519687082', '126', null, '园区服务', 'Housecontent', '2', '1600', '1000.00', '0.00', '', null, '雷全', null, null, null, null, '0', '1', '2', '1', '2', null, null, '2', null, '1', '0', null, '2018-09-03 13:19:28', '2018-09-10 14:42:27');
INSERT INTO `cmf_order` VALUES ('94', '15359519905642', '126', null, '资质转让', 'Companycontent', '2', '59', '0.00', '0.00', '', null, '雷全', null, null, null, null, '0', '1', '2', '1', '1', null, null, '2', null, '1', '0', null, '2018-09-03 13:19:50', '2018-09-03 13:19:50');
INSERT INTO `cmf_order` VALUES ('95', '15359519972805', '126', null, '资质转让', 'Companycontent', '3', '2000000', '12000.00', '0.00', '', null, '雷全', null, null, null, null, '0', '1', '1', '3', '1', null, null, '2', null, '1', '0', null, '2018-09-03 13:19:57', '2018-09-03 13:21:13');
INSERT INTO `cmf_order` VALUES ('96', '15359520008759', '126', null, '资质转让', 'Companycontent', '8', '1000000', '10000.00', '0.00', '', null, '雷全', null, null, null, null, '0', '1', '1', '1', '1', null, null, '2', null, '1', '0', null, '2018-09-03 13:20:00', '2018-09-03 13:21:06');
INSERT INTO `cmf_order` VALUES ('97', '15359565838777', '126', null, '资质转让', 'Companycontent', '8', '1000000', '100000.00', '0.00', '', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, null, '0', '1', '1', '1', '1', null, null, '2', null, '1', '0', null, '2018-09-03 14:36:23', '2018-09-03 14:45:00');
INSERT INTO `cmf_order` VALUES ('98', '15359572408812', '126', null, '资质转让', 'Companycontent', '8', '1000000', '100000.00', '99500.00', '', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', '', null, null, null, '0', '1', '2', '1', '4', null, null, '2', '32', '1', '0', null, '2018-09-03 14:47:20', '2018-09-06 10:06:48');
INSERT INTO `cmf_order` VALUES ('99', '15362253394418', '126', null, '财税管理', 'Servicecontent', '8', '200', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-06 17:15:39', '2018-09-06 17:15:39');
INSERT INTO `cmf_order` VALUES ('100', '15365661525964', '99', null, '财税管理', 'servicecontent', '8', '800', '200.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', null, '雷全', null, '杭州风步科技', null, null, '0', '1', '1', '1', '2', null, null, '2', null, '1', '0', null, '2018-09-10 15:55:52', '2018-09-10 15:55:52');
INSERT INTO `cmf_order` VALUES ('101', '15365739942340', '126', null, '财税管理', 'Servicecontent', '8', '200', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:06:34', '2018-09-10 18:06:34');
INSERT INTO `cmf_order` VALUES ('102', '15365741786006', '126', null, '财税管理', 'Servicecontent', '8', '200', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:09:38', '2018-09-10 18:09:38');
INSERT INTO `cmf_order` VALUES ('103', '15365742713504', '126', null, '财税管理', 'Servicecontent', '8', '200', '1111.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:11:11', '2018-09-10 18:11:11');
INSERT INTO `cmf_order` VALUES ('104', '15365747775555', '126', null, '财税管理', 'Servicecontent', '8', '200', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:19:37', '2018-09-10 18:19:37');
INSERT INTO `cmf_order` VALUES ('105', '15365749237787', '126', null, '财税管理', 'Servicecontent', '8', '200', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:22:03', '2018-09-10 18:22:03');
INSERT INTO `cmf_order` VALUES ('106', '15365753553146', '126', null, '财税管理', 'Servicecontent', '8', '200', '1000.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:29:15', '2018-09-10 18:29:15');
INSERT INTO `cmf_order` VALUES ('107', '15365757848955', '126', null, '财税管理', 'Servicecontent', '8', '200', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:36:24', '2018-09-10 18:36:24');
INSERT INTO `cmf_order` VALUES ('108', '15365758354469', '126', null, '财税管理', 'Servicecontent', '8', '200', '-5.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:37:15', '2018-09-10 18:37:15');
INSERT INTO `cmf_order` VALUES ('109', '15365758687973', '126', null, '财税管理', 'Servicecontent', '8', '200', '0.00', '0.00', '税务种类:小规模纳税人,付款方式:季付', '{\"product_name\":\"\\u70b9\\u70b9\\u6ef4\\u6ef4\",\"product_picture\":\"\\/yikesong\\/public\\/uploads\\/images\\/20180416\\\\bf50990153020093cf9c843cd569afec.png\",\"filter\":[\"\\u6709\\u6d41\\u6c34\"]}', '雷全', null, null, null, '1', '89', '1', '2', '3', '2', null, '2018-10-05 16:30:47', '2', null, '1', '0', null, '2018-09-10 18:37:48', '2018-09-10 18:37:48');

-- ----------------------------
-- Table structure for cmf_order_schedule
-- ----------------------------
DROP TABLE IF EXISTS `cmf_order_schedule`;
CREATE TABLE `cmf_order_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `name` varchar(255) DEFAULT NULL COMMENT '所属服务名',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COMMENT='订单进度表';

-- ----------------------------
-- Records of cmf_order_schedule
-- ----------------------------
INSERT INTO `cmf_order_schedule` VALUES ('1', '24', '1', '材料签字', '2017-06-09 17:26:01');
INSERT INTO `cmf_order_schedule` VALUES ('2', '24', '1', '工商登记', '2017-06-09 17:32:11');
INSERT INTO `cmf_order_schedule` VALUES ('3', '1', '1', '材料签字', '2017-06-12 13:33:51');
INSERT INTO `cmf_order_schedule` VALUES ('4', '24', '1', '执照刻章', '2017-06-12 14:16:29');
INSERT INTO `cmf_order_schedule` VALUES ('5', '24', '1', '税务办理', '2017-06-12 14:16:33');
INSERT INTO `cmf_order_schedule` VALUES ('6', '1', '2', '工商登记', '2017-06-14 15:04:40');
INSERT INTO `cmf_order_schedule` VALUES ('7', '2', '2', '材料签字', '2017-06-14 15:05:01');
INSERT INTO `cmf_order_schedule` VALUES ('8', '2', '2', '工商登记', '2017-06-14 15:05:17');
INSERT INTO `cmf_order_schedule` VALUES ('9', '2', '2', '执照刻章', '2017-06-14 15:05:21');
INSERT INTO `cmf_order_schedule` VALUES ('10', '2', '2', '税务办理', '2017-06-14 15:09:38');
INSERT INTO `cmf_order_schedule` VALUES ('11', '33', '11', '材料签字', '2017-08-29 15:08:43');
INSERT INTO `cmf_order_schedule` VALUES ('12', '33', '11', '工商登记', '2017-08-29 15:08:59');
INSERT INTO `cmf_order_schedule` VALUES ('13', '33', '11', '执照刻章', '2017-08-29 15:09:01');
INSERT INTO `cmf_order_schedule` VALUES ('14', '33', '11', '税务办理', '2017-08-29 15:09:03');
INSERT INTO `cmf_order_schedule` VALUES ('15', '51', '17', '材料签字', '2017-09-11 18:41:33');
INSERT INTO `cmf_order_schedule` VALUES ('16', '52', '20', '材料签字', '2017-10-23 18:10:53');
INSERT INTO `cmf_order_schedule` VALUES ('17', '55', '11', '材料签字', '2017-10-31 13:21:57');
INSERT INTO `cmf_order_schedule` VALUES ('18', '55', '11', '工商登记', '2017-10-31 13:22:09');
INSERT INTO `cmf_order_schedule` VALUES ('19', '61', '11', '材料签字', '2018-02-24 16:39:49');
INSERT INTO `cmf_order_schedule` VALUES ('20', '61', '11', '工商登记', '2018-02-24 16:39:58');
INSERT INTO `cmf_order_schedule` VALUES ('21', '61', '11', '执照刻章', '2018-02-24 16:40:03');
INSERT INTO `cmf_order_schedule` VALUES ('22', '61', '11', '税务办理', '2018-02-24 16:40:08');
INSERT INTO `cmf_order_schedule` VALUES ('23', '61', '11', '银行开户', '2018-02-24 16:40:09');
INSERT INTO `cmf_order_schedule` VALUES ('24', '62', '9', '材料签字', '2018-03-04 14:26:55');
INSERT INTO `cmf_order_schedule` VALUES ('25', '62', '9', '工商登记', '2018-03-04 14:27:18');
INSERT INTO `cmf_order_schedule` VALUES ('26', '62', '9', '执照刻章', '2018-03-04 14:27:21');
INSERT INTO `cmf_order_schedule` VALUES ('27', '62', '9', '税务办理', '2018-03-04 14:27:23');
INSERT INTO `cmf_order_schedule` VALUES ('28', '62', '9', '银行开户', '2018-03-04 14:27:26');
INSERT INTO `cmf_order_schedule` VALUES ('29', '74', '99', '材料签字', '2018-03-30 14:58:05');
INSERT INTO `cmf_order_schedule` VALUES ('30', '74', '99', '工商登记', '2018-04-02 15:27:52');
INSERT INTO `cmf_order_schedule` VALUES ('31', '74', '99', '执照刻章', '2018-04-02 15:45:52');
INSERT INTO `cmf_order_schedule` VALUES ('32', '74', '99', '税务办理', '2018-04-02 16:52:05');
INSERT INTO `cmf_order_schedule` VALUES ('33', '74', '99', '银行开户', '2018-04-02 16:57:16');
INSERT INTO `cmf_order_schedule` VALUES ('34', '77', '99', '材料签字', '2018-04-02 17:09:12');
INSERT INTO `cmf_order_schedule` VALUES ('35', '77', '99', '工商登记', '2018-04-02 17:10:30');
INSERT INTO `cmf_order_schedule` VALUES ('37', '77', '99', '执照刻章', '2018-08-07 15:15:27');
INSERT INTO `cmf_order_schedule` VALUES ('38', '77', '99', '税务办理', '2018-08-07 15:46:58');
INSERT INTO `cmf_order_schedule` VALUES ('54', '77', '99', '银行开户', '2018-08-07 17:04:33');
INSERT INTO `cmf_order_schedule` VALUES ('55', '98', '126', '合同签订', '2018-09-03 17:15:03');
INSERT INTO `cmf_order_schedule` VALUES ('56', '98', '126', '名称校准', '2018-09-03 17:15:07');
INSERT INTO `cmf_order_schedule` VALUES ('65', '98', '126', '地址审批', '2018-09-06 14:33:55');
INSERT INTO `cmf_order_schedule` VALUES ('68', '98', '126', '网上申报', '2018-09-06 14:40:44');
INSERT INTO `cmf_order_schedule` VALUES ('69', '60', '11', '材料签字', '2018-09-10 11:29:13');

-- ----------------------------
-- Table structure for cmf_pact
-- ----------------------------
DROP TABLE IF EXISTS `cmf_pact`;
CREATE TABLE `cmf_pact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `temp_id` int(11) DEFAULT '0' COMMENT '合同模板id',
  `name` varchar(150) DEFAULT '' COMMENT '合同名称',
  `status` tinyint(1) DEFAULT '1' COMMENT '1等待雇主签订  2已签订 3删除',
  `facilitator` varchar(255) DEFAULT NULL COMMENT '服务商名称',
  `master` varchar(50) DEFAULT NULL COMMENT '雇主名称',
  `liable` varchar(50) DEFAULT '' COMMENT '合同负责人（不一定是订单负责人）',
  `options` varchar(100) DEFAULT '' COMMENT '合同项',
  `detail` text COMMENT '合同项及其内容',
  `master_sign_time` datetime DEFAULT NULL COMMENT '雇主h合同签署日期',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderId` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COMMENT='合同表';

-- ----------------------------
-- Records of cmf_pact
-- ----------------------------
INSERT INTO `cmf_pact` VALUES ('1', '1', '1000', '0', '', '3', '你好', '', '', '', '', '2017-06-07 17:07:02', '2017-06-07 17:07:02', null);
INSERT INTO `cmf_pact` VALUES ('3', '1', '23', '0', '', '1', '你好', '', '', '', '', '2017-06-07 17:08:02', '2017-06-07 17:07:03', null);
INSERT INTO `cmf_pact` VALUES ('4', '1', '22', '0', '', '1', '你好', '', '', '', '', '2017-06-07 17:09:02', '2017-06-07 17:07:04', null);
INSERT INTO `cmf_pact` VALUES ('5', '2', '2', '2', '', '3', '杭州风步科技', '', '', '', '', '2017-06-07 17:10:02', '2017-06-07 17:07:05', null);
INSERT INTO `cmf_pact` VALUES ('6', '1', '3', '0', '', '3', '你好', '', '', '', '', '2017-06-07 17:11:02', '2017-06-07 17:07:06', null);
INSERT INTO `cmf_pact` VALUES ('7', '1', '4', '0', '', '3', '你好', '', '', '', '', '2017-06-07 17:12:02', '2017-06-07 17:07:07', null);
INSERT INTO `cmf_pact` VALUES ('8', '1', '5', '0', '', '3', '你好', '', '', '', '', null, '2017-06-07 17:07:08', null);
INSERT INTO `cmf_pact` VALUES ('9', '1', '6', '0', '', '3', '你好', '', '', '', '', null, '2017-06-07 17:07:09', null);
INSERT INTO `cmf_pact` VALUES ('10', '1', '7', '0', '', '3', '你好', '', '', '', '', null, '2017-06-07 17:07:10', null);
INSERT INTO `cmf_pact` VALUES ('11', '1', '8', '0', '', '3', '你好1234', 'asda', '', '', '', '2017-06-09 15:11:40', '2017-06-07 17:07:11', null);
INSERT INTO `cmf_pact` VALUES ('12', '17', '28', '0', '', '1', '杭州风步信息技术有限公司', null, '', '', '', null, '2017-06-22 18:16:07', null);
INSERT INTO `cmf_pact` VALUES ('13', '20', '29', '0', '', '2', '杭州风步信息技术有限公司', '徐', '', '', '', '2017-06-26 16:19:32', '2017-06-26 15:17:01', null);
INSERT INTO `cmf_pact` VALUES ('14', '20', '30', '0', '', '2', '222', '徐', '', '', '', '2017-06-30 19:39:06', '2017-06-30 19:38:53', null);
INSERT INTO `cmf_pact` VALUES ('15', '58', '35', '0', '', '3', '大沙发', '虞喜', '', '', '', '2017-08-30 09:44:30', '2017-08-29 14:59:50', null);
INSERT INTO `cmf_pact` VALUES ('16', '17', '43', '0', '', '3', '杭州风步', null, '', '', '', null, '2017-09-08 18:18:45', null);
INSERT INTO `cmf_pact` VALUES ('42', '20', null, '0', '', '1', 'ceshi', null, '', '', '', null, '2017-09-11 09:16:21', null);
INSERT INTO `cmf_pact` VALUES ('44', '17', null, '0', '', '1', '杭州风步信息技术有限公司', null, '', '', '', null, '2017-09-11 17:12:55', null);
INSERT INTO `cmf_pact` VALUES ('45', '17', null, '0', '', '1', '风步', null, '', '', '', null, '2017-09-11 17:15:30', null);
INSERT INTO `cmf_pact` VALUES ('46', '17', null, '0', '', '2', '风步', '吴老师', '', '', '', '2017-09-11 17:16:57', '2017-09-11 17:16:03', null);
INSERT INTO `cmf_pact` VALUES ('47', '17', '47', '0', '', '1', '一棵松', null, '', '', '', null, '2017-09-11 17:37:08', null);
INSERT INTO `cmf_pact` VALUES ('48', '17', '48', '0', '', '3', '一棵松', null, '', '', '', null, '2017-09-11 17:43:33', null);
INSERT INTO `cmf_pact` VALUES ('49', '17', '49', '0', '', '2', '一棵松', '吴老师', '', '', '', '2017-09-11 18:23:16', '2017-09-11 18:22:34', null);
INSERT INTO `cmf_pact` VALUES ('50', '17', null, '0', '', '2', '一棵松', '吴老师', '', '', '', '2017-09-11 18:25:04', '2017-09-11 18:24:53', null);
INSERT INTO `cmf_pact` VALUES ('51', '17', '51', '0', '', '2', '一棵松', '吴老师', '', '', '', '2017-09-11 18:38:41', '2017-09-11 18:38:17', null);
INSERT INTO `cmf_pact` VALUES ('52', '20', '52', '0', '', '2', 'hz', '徐', '', '', '', '2017-10-23 18:06:26', '2017-10-23 18:05:55', null);
INSERT INTO `cmf_pact` VALUES ('53', '11', '33', '0', '', '2', 'sad', '郑蒙飞', '', '', '', '2018-02-23 12:56:11', '2017-10-23 18:51:45', null);
INSERT INTO `cmf_pact` VALUES ('54', '20', '53', '0', '', '1', '12', null, '', '', '', null, '2017-10-23 18:53:43', null);
INSERT INTO `cmf_pact` VALUES ('55', '11', '60', '0', '', '2', '杭州一棵松企业管理有限公司', '郑蒙飞', '', '', '', '2018-02-24 16:30:55', '2018-02-24 16:26:54', null);
INSERT INTO `cmf_pact` VALUES ('56', '11', '61', '0', '', '2', '阿萨德', '郑蒙飞', '', '', '', '2018-02-24 16:37:18', '2018-02-24 16:36:28', null);
INSERT INTO `cmf_pact` VALUES ('83', '98', '69', '1', '', '1', '杭州风步科技', '刘德华', '', '', '{\"pactId\":\"83\",\"rr\":\"<p>\\u4e09\\u751f\\u4e09\\u4e16<\\/p>\",\"\\u53cc\\u65b9\\u8d23\\u4efb\":\"<p>\\u54c8\\u54c8\\u54c8\\u54c8<\\/p>\",\"\\u652f\\u4ed8\\u9a8c\\u6536\":\"<p>\\u541e\\u541e\\u5410\\u5410<\\/p>\",\"\\u5de5\\u671f\":\"<p>\\u53cd\\u53cd\\u590d\\u590d<\\/p>\"}', null, '2018-08-02 18:02:45', '2018-08-02 18:30:51');
INSERT INTO `cmf_pact` VALUES ('81', '58', '1', '2', '', '1', '杭州风步科技', '虞喜', '', '', '{\"\\u5de5\\u671f\":\"&lt;p&gt;555&lt;\\/p&gt;\",\"\\u6838\\u5fc3\\u529f\\u80fd\":\"&lt;p&gt;666&lt;\\/p&gt;\",\"\\u652f\\u4ed8\\u9a8c\\u6536\":\"&lt;p&gt;\\u548c\\u9644\\u4ef6\\u82b1\\u8d39&lt;\\/p&gt;\"}', null, '2018-08-02 16:14:07', '2018-08-02 16:14:07');
INSERT INTO `cmf_pact` VALUES ('58', '99', '70', '0', '', '2', '杭州风步科技', '雷全', '', '', '', '2018-03-27 14:01:51', '2018-03-27 10:58:36', null);
INSERT INTO `cmf_pact` VALUES ('73', '99', '76', '0', '', '1', 'gg', null, '', '', '{\"facilitator\":\"gg\",\"gongqi\":\"<p>jhg<\\/p>\",\"core\":\"<p>jjh<\\/p>\",\"payaccept\":\"<p>zzz<\\/p>\",\"duty\":\"<p>sss<\\/p>\",\"yy\":\"<p>sss<\\/p>\"}', null, '2018-04-01 18:34:04', null);
INSERT INTO `cmf_pact` VALUES ('74', '99', '78', '2', '', '1', 'llll', null, '', '', '{\"gongqi\":\"gg\",\"core\":\"gggg\",\"payaccept\":\"g\",\"facilitator\":\"llll\",\"mId\":\"99\"}', null, '2018-04-03 19:16:45', null);
INSERT INTO `cmf_pact` VALUES ('79', '99', '79', '1', '', '1', 'hh', null, '', '', '{\"gongqi\":\"fff\",\"payaccept\":\"\\u6253\\u65ad\\u70b9\",\"duty\":\"\\u8bed\\u97f3\",\"yy\":\"ggg\",\"facilitator\":\"hh\"}', null, '2018-04-03 19:33:53', null);
INSERT INTO `cmf_pact` VALUES ('84', '126', '126', '1', '', '2', '杭州风步科技', '雷时全', '', '', '{\"rr\":\"<p>\\u54c8\\u54c8\\u5171\\u548c\\u56fd<\\/p>\",\"\\u53cc\\u65b9\\u8d23\\u4efb\":\"<p>\\u975e\\u51e1\\u54e5<\\/p>\",\"\\u652f\\u4ed8\\u9a8c\\u6536\":\"<p>\\u8d39\\u5927\\u5e45\\u5ea6\\u53d1<\\/p>\",\"\\u5de5\\u671f\":\"<p>\\u574e\\u574e\\u5777\\u5777\\u6269\\u6269<\\/p>\"}', '2018-09-03 10:48:08', '2018-08-02 18:48:50', '2018-09-03 10:48:08');
INSERT INTO `cmf_pact` VALUES ('85', null, '89', '5', '阿里巴巴财税合同', '1', null, '阿里巴巴', '雷全', '', '{&quot;gongqi&quot;:&quot;fff&quot;,&quot;payaccept&quot;:&quot;\\u6253\\u65ad\\u70b9&quot;,&quot;duty&quot;:&quot;\\u8bed\\u97f3&quot;,&quot;yy&quot;:&quot;ggg&quot;,&quot;facilitator&quot;:&quot;hh&quot;}', null, '2018-09-05 19:20:42', '2018-09-05 19:20:42');
INSERT INTO `cmf_pact` VALUES ('86', '126', '93', '5', '美团财税合同', '1', null, '美团', '雷全', '', '{&quot;gongqi&quot;:&quot;fff&quot;,&quot;payaccept&quot;:&quot;\\u6253\\u65ad\\u70b9&quot;,&quot;duty&quot;:&quot;\\u8bed\\u97f3&quot;,&quot;yy&quot;:&quot;ggg&quot;,&quot;facilitator&quot;:&quot;hh&quot;}', null, '2018-09-05 19:25:33', '2018-09-05 19:25:33');
INSERT INTO `cmf_pact` VALUES ('87', '126', '94', '5', '饿了么财税合同', '1', null, '饿了么', '雷全', '[\"3\",\"4\",\"8\"]', '{\"gongqi\":\"gg\",\"core\":\"gggg\",\"payaccept\":\"g\",\"facilitator\":\"llll\",\"mId\":\"99\"}', null, '2018-09-05 19:56:38', '2018-09-05 19:56:38');
INSERT INTO `cmf_pact` VALUES ('89', '126', '98', '5', '', '1', null, '阿里巴巴', '雷全', '[\"3\",\"4\",\"8\"]', '{\"\\u5de5\\u671f\":\"<p>\\u4e00\\u4e2a<\\/p>\",\"\\u652f\\u4ed8\\u9a8c\\u6536\":\"<p>\\u884c\\u4e1a<\\/p>\",\"\\u53cc\\u65b9\\u8d23\\u4efb\":\"<p>\\u901a\\u878d\\u901a\\u878d<br\\/><\\/p>\",\"\\u652f\\u4ed8\\u65b9\\u5f0f\":\"<p>\\u5b63\\u4ed8<\\/p>\"}', null, '2018-09-05 20:27:18', '2018-09-05 20:27:18');
INSERT INTO `cmf_pact` VALUES ('90', '126', '91', '5', '饿了么财税合同', '1', null, '饿了么', '雷全', '[\"3\",\"4\",\"8\"]', '{&quot;gongqi&quot;:&quot;fff&quot;,&quot;payaccept&quot;:&quot;\\u6253\\u65ad\\u70b9&quot;,&quot;duty&quot;:&quot;\\u8bed\\u97f3&quot;,&quot;yy&quot;:&quot;ggg&quot;,&quot;facilitator&quot;:&quot;hh&quot;}', null, '2018-09-05 21:03:13', '2018-09-05 21:03:13');
INSERT INTO `cmf_pact` VALUES ('91', '126', '85', '5', '验资审计合同', '1', null, '中国石化', '雷全', '[\"3\",\"4\",\"8\"]', '{\"\\u53cc\\u65b9\\u8d23\\u4efb\":\"<p>\\u59d1\\u59d1\\u59d1\\u7236\\u89c4\\u8303<\\/p>\",\"\\u652f\\u4ed8\\u9a8c\\u6536\":\"<p>\\u4e2a\\u975e\\u5b98\\u65b9\\u4e2a<\\/p>\",\"\\u6838\\u5fc3\\u529f\\u80fd\":\"<p>\\u53d1\\u5e7f\\u544a\\u6cd5\\u89c4<\\/p>\",\"\\u5de5\\u671f\":\"<p>\\u8179\\u80a1\\u6c9f\\u7ba1<\\/p>\"}', null, '2018-09-10 15:39:47', '2018-09-10 16:46:04');

-- ----------------------------
-- Table structure for cmf_pact_option
-- ----------------------------
DROP TABLE IF EXISTS `cmf_pact_option`;
CREATE TABLE `cmf_pact_option` (
  `option_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '合同项名称',
  `fieldname` varchar(50) NOT NULL DEFAULT '' COMMENT '合同项字段名',
  `description` varchar(100) NOT NULL COMMENT '合同项描述',
  `icon` varchar(100) DEFAULT '' COMMENT '合同项图标',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='合同项目表';

-- ----------------------------
-- Records of cmf_pact_option
-- ----------------------------
INSERT INTO `cmf_pact_option` VALUES ('1', '工期', 'gongqi', '项目完成期限', '', '2017-06-15 15:19:30', '2018-09-05 17:06:34');
INSERT INTO `cmf_pact_option` VALUES ('2', '核心功能', 'core', '项目主要功能', '', '2017-06-15 15:19:30', null);
INSERT INTO `cmf_pact_option` VALUES ('3', '支付验收', 'payaccept', '支付及验收标准', '', '2017-06-15 15:19:30', null);
INSERT INTO `cmf_pact_option` VALUES ('4', '双方责任', 'duty', '双方违约责任', '', '2017-06-15 15:19:30', null);
INSERT INTO `cmf_pact_option` VALUES ('5', 'rr', 'yy', '7', '', '2017-06-15 15:19:30', null);
INSERT INTO `cmf_pact_option` VALUES ('8', '支付方式', 'mtc', '1季付2半年付3年付', '', '2018-09-05 16:50:53', '2018-09-05 17:19:14');

-- ----------------------------
-- Table structure for cmf_pact_temp
-- ----------------------------
DROP TABLE IF EXISTS `cmf_pact_temp`;
CREATE TABLE `cmf_pact_temp` (
  `temp_id` int(20) NOT NULL AUTO_INCREMENT,
  `options` varchar(50) NOT NULL DEFAULT '' COMMENT '模板所包含合同项',
  `description` varchar(200) DEFAULT '' COMMENT '模板描述',
  `picture` varchar(300) DEFAULT '' COMMENT '模板图片',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`temp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='合同模板表';

-- ----------------------------
-- Records of cmf_pact_temp
-- ----------------------------
INSERT INTO `cmf_pact_temp` VALUES ('1', '[\"1\",\"3\",\"4\",\"5\"]', '模板1', '20180802\\00ed93a6cf1f22170706830ee375b39f.png', null);
INSERT INTO `cmf_pact_temp` VALUES ('2', '[\"1\",\"2\",\"3\"]', '模板2', '20180802\\ffb629e969e39b46967ce371bc589149.png', null);
INSERT INTO `cmf_pact_temp` VALUES ('4', '[\"1\",\"2\",\"3\",\"4\"]', '新模板', '20180801\\fd3b8fa313fa8c660a909135f98729f4.png', '2018-08-01 10:42:04');
INSERT INTO `cmf_pact_temp` VALUES ('5', '[\"3\",\"4\",\"8\"]', '财税管理合同模板', '', '2018-09-05 16:58:50');

-- ----------------------------
-- Table structure for cmf_pay_log
-- ----------------------------
DROP TABLE IF EXISTS `cmf_pay_log`;
CREATE TABLE `cmf_pay_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `order_id` int(10) DEFAULT '0',
  `trade_no` varchar(60) DEFAULT NULL COMMENT '支付宝订单号',
  `out_trade_no` varchar(60) DEFAULT NULL COMMENT '自己的订单号',
  `money` decimal(10,2) NOT NULL,
  `seller_id` varchar(40) DEFAULT NULL COMMENT '支付宝商户号',
  `type` tinyint(1) NOT NULL COMMENT '类型 1支付宝 2 微信',
  `params` text COMMENT '生成校验字符串',
  `sign` varchar(60) DEFAULT NULL COMMENT '生成签名 支付宝经过MD5加密 ',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1 未支付确认 2确认成功  3 确认失败  4退款成功 5 退款失败 ',
  `style` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 支付订单 2 退款 3 支付尾款 4 小程序付款 5 充值',
  `refund_no` varchar(30) DEFAULT NULL COMMENT '退款单号 如果退款',
  `user_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '用户付款时的IP地址',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '确认状态时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=434 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_pay_log
-- ----------------------------
INSERT INTO `cmf_pay_log` VALUES ('431', '123', '83', null, '1231535365781446316', '0.01', null, '2', null, '184ca36cd0492b6c59fd1acb53f5c475', '1', '1', null, '::1', '1535365782', '0');
INSERT INTO `cmf_pay_log` VALUES ('432', '123', '83', null, '1231535420739510723', '0.01', null, '2', null, '8258b629fc91b99835e3ee140933dc0d', '1', '1', null, '::1', '1535420740', '0');
INSERT INTO `cmf_pay_log` VALUES ('433', '126', '85', null, '1261535961672624679', '0.01', null, '2', null, 'ab54c05ddd24d310547950100533b4d8', '1', '1', null, '::1', '1535961674', '0');

-- ----------------------------
-- Table structure for cmf_phone
-- ----------------------------
DROP TABLE IF EXISTS `cmf_phone`;
CREATE TABLE `cmf_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` char(50) DEFAULT NULL COMMENT '所属服务名称',
  `phone` char(50) DEFAULT NULL COMMENT '客服电话',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='租房找房、资质转让、天猫转让等客服电话管理';

-- ----------------------------
-- Records of cmf_phone
-- ----------------------------
INSERT INTO `cmf_phone` VALUES ('2', '园区服务', '15906606006', '2017-06-05 12:59:06');
INSERT INTO `cmf_phone` VALUES ('3', '资质转让', '010234567896', '2017-06-05 13:13:11');
INSERT INTO `cmf_phone` VALUES ('4', '天猫转让', '01022345678', '2017-06-05 13:18:50');

-- ----------------------------
-- Table structure for cmf_plugins
-- ----------------------------
DROP TABLE IF EXISTS `cmf_plugins`;
CREATE TABLE `cmf_plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '插件名，英文',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `description` text COMMENT '插件描述',
  `type` tinyint(2) DEFAULT '0' COMMENT '插件类型, 1:网站；8;微信',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态；1开启；',
  `config` text COMMENT '插件配置',
  `hooks` varchar(255) DEFAULT NULL COMMENT '实现的钩子;以“，”分隔',
  `has_admin` tinyint(2) DEFAULT '0' COMMENT '插件是否有后台管理界面',
  `author` varchar(50) DEFAULT '' COMMENT '插件作者',
  `version` varchar(20) DEFAULT '' COMMENT '插件版本号',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `listorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of cmf_plugins
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_posts
-- ----------------------------
DROP TABLE IF EXISTS `cmf_posts`;
CREATE TABLE `cmf_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned DEFAULT '0' COMMENT '发表者id',
  `post_keywords` varchar(150) NOT NULL COMMENT 'seo keywords',
  `post_source` varchar(150) DEFAULT NULL COMMENT '转载文章的来源',
  `post_date` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post发布日期',
  `post_content` longtext COMMENT 'post内容',
  `post_title` text COMMENT 'post标题',
  `post_excerpt` text COMMENT 'post摘要',
  `post_status` int(2) DEFAULT '1' COMMENT 'post状态，1已审核，0未审核,3删除',
  `comment_status` int(2) DEFAULT '1' COMMENT '评论状态，1允许，0不允许',
  `post_modified` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post更新时间，可在前台修改，显示给用户',
  `post_content_filtered` longtext,
  `post_parent` bigint(20) unsigned DEFAULT '0' COMMENT 'post的父级post id,表示post层级关系',
  `post_type` int(2) DEFAULT '1' COMMENT 'post类型，1文章,2页面',
  `post_mime_type` varchar(100) DEFAULT '',
  `comment_count` bigint(20) DEFAULT '0',
  `smeta` text COMMENT 'post的扩展字段，保存相关扩展属性，如缩略图；格式为json',
  `post_hits` int(11) DEFAULT '0' COMMENT 'post点击数，查看数',
  `post_like` int(11) DEFAULT '0' COMMENT 'post赞数',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶 1置顶； 0不置顶',
  `recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐 1推荐 0不推荐',
  PRIMARY KEY (`id`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`id`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`),
  KEY `post_date` (`post_date`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Portal文章表';

-- ----------------------------
-- Records of cmf_posts
-- ----------------------------
INSERT INTO `cmf_posts` VALUES ('1', '1', 'asdfa', 'asdfa', '2017-05-27 09:11:53', '<p><img src=\"/data/upload/ueditor/20170527/5928d2776967c.png\" title=\"headicon.png\" alt=\"headicon.png\"/></p>', 'asdafasdfa', 'safasdfda', '1', '1', '2017-05-27 09:12:28', null, '0', '1', '', '0', '{\"thumb\":\"\",\"template\":\"\"}', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for cmf_role
-- ----------------------------
DROP TABLE IF EXISTS `cmf_role`;
CREATE TABLE `cmf_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父角色ID',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态 0删除 1正常 2禁用',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `listorder` int(10) DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of cmf_role
-- ----------------------------
INSERT INTO `cmf_role` VALUES ('1', '超级管理员', null, '1', '具有最高权限', '2018-07-27 17:51:56', '2018-07-27 18:33:03', '0');
INSERT INTO `cmf_role` VALUES ('2', '员工', null, '1', '有一定权限', '2018-07-27 17:51:56', '2018-07-27 18:09:03', '0');

-- ----------------------------
-- Table structure for cmf_role_user
-- ----------------------------
DROP TABLE IF EXISTS `cmf_role_user`;
CREATE TABLE `cmf_role_user` (
  `role_id` int(11) unsigned DEFAULT '0' COMMENT '角色 id',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色对应表';

-- ----------------------------
-- Records of cmf_role_user
-- ----------------------------
INSERT INTO `cmf_role_user` VALUES ('3', '2');
INSERT INTO `cmf_role_user` VALUES ('1', '3');
INSERT INTO `cmf_role_user` VALUES ('1', '4');
INSERT INTO `cmf_role_user` VALUES ('1', '5');

-- ----------------------------
-- Table structure for cmf_route
-- ----------------------------
DROP TABLE IF EXISTS `cmf_route`;
CREATE TABLE `cmf_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路由id',
  `full_url` varchar(255) DEFAULT NULL COMMENT '完整url， 如：portal/list/index?id=1',
  `url` varchar(255) DEFAULT NULL COMMENT '实际显示的url',
  `listorder` int(5) DEFAULT '0' COMMENT '排序，优先级，越小优先级越高',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，1：启用 ;0：不启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='url路由表';

-- ----------------------------
-- Records of cmf_route
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_servicecontent
-- ----------------------------
DROP TABLE IF EXISTS `cmf_servicecontent`;
CREATE TABLE `cmf_servicecontent` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `service` varchar(255) DEFAULT NULL COMMENT '所属服务',
  `picture` text COMMENT '轮播图',
  `phone` char(30) DEFAULT NULL COMMENT '客服电话',
  `price` double(11,2) DEFAULT NULL COMMENT '价格',
  `introduce_mess` text COMMENT '信息介绍',
  `trade_type` text COMMENT '交易方式',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='服务详情表';

-- ----------------------------
-- Records of cmf_servicecontent
-- ----------------------------
INSERT INTO `cmf_servicecontent` VALUES ('4', '验资审计', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '020-123456', '1600.00', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8da4c9174.jpg&quot; style=&quot;&quot; title=&quot;03_01.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8da802049.jpg&quot; style=&quot;&quot; title=&quot;03_02.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8db005378.png&quot; title=&quot;流程.png&quot; alt=&quot;流程.png&quot;/&gt;&lt;/p&gt;', '2017-05-31 14:04:26', null);
INSERT INTO `cmf_servicecontent` VALUES ('5', '公司注册', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '400-0020-680', '1600.00', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8cdb89c4d.jpg&quot; style=&quot;&quot; title=&quot;01-公司注册_01.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8cdec51d9.jpg&quot; style=&quot;&quot; title=&quot;01-公司注册_02.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8ce29eb74.jpg&quot; style=&quot;&quot; title=&quot;01-公司注册_03.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8ce60b7b8.jpg&quot; style=&quot;&quot; title=&quot;01-公司注册_04.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8cedcddff.png&quot; title=&quot;流程.png&quot; alt=&quot;流程.png&quot;/&gt;&lt;/p&gt;', '2017-06-02 12:44:49', null);
INSERT INTO `cmf_servicecontent` VALUES ('7', '政策规划', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '400-0020-680', '0.00', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8fa7cf627.jpg&quot; title=&quot;04-政策规划.jpg&quot; alt=&quot;04-政策规划.jpg&quot;/&gt;&lt;/p&gt;', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8fad365fe.png&quot; title=&quot;流程.png&quot; alt=&quot;流程.png&quot;/&gt;&lt;/p&gt;', '2017-06-22 17:37:19', null);
INSERT INTO `cmf_servicecontent` VALUES ('6', '工商变更', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '400-0020-680', '800.00', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8d510a9ae.jpg&quot; style=&quot;&quot; title=&quot;02-工商变更_01.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8d5532a23.jpg&quot; style=&quot;&quot; title=&quot;02-工商变更_02.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8d583baef.jpg&quot; style=&quot;&quot; title=&quot;02-工商变更_03.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b8d61007e0.png&quot; title=&quot;流程.png&quot; alt=&quot;流程.png&quot;/&gt;&lt;/p&gt;', '2017-06-22 17:16:35', null);
INSERT INTO `cmf_servicecontent` VALUES ('8', '财税管理', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '400-0020-680', '200.00', '<p>&nbsp;更丰富付付付付付付付付</p>', '<p>fffffffff</p>', '2017-06-22 17:57:19', '2018-08-18 16:49:02');
INSERT INTO `cmf_servicecontent` VALUES ('14', '呃呃呃', '20180802\\4e01e75b6d45131585abc5d989c343ed.png', '5000', '2000.00', '<p>你不不不</p>', '<p>&nbsp;哈哈哈呵呵或</p>', '2018-08-02 17:54:19', null);
INSERT INTO `cmf_servicecontent` VALUES ('9', 'APP制作', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '18658876499', '1500.00', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b95360727d.jpg&quot; title=&quot;05-app制作.jpg&quot; alt=&quot;05-app制作.jpg&quot;/&gt;&lt;/p&gt;', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b953d72315.png&quot; title=&quot;流程.png&quot; alt=&quot;流程.png&quot;/&gt;&lt;/p&gt;', '2017-06-22 18:00:55', null);
INSERT INTO `cmf_servicecontent` VALUES ('10', '法律咨询', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '400-0020-680', '100.00', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b96de30fe1.jpg&quot; title=&quot;07-法律咨询.jpg&quot; alt=&quot;07-法律咨询.jpg&quot; width=&quot;367&quot; height=&quot;703&quot; style=&quot;width: 367px; height: 703px;&quot;/&gt;&lt;/p&gt;', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170622/594b96e332dbc.png&quot; title=&quot;流程.png&quot; alt=&quot;流程.png&quot; width=&quot;370&quot; height=&quot;75&quot; style=&quot;width: 370px; height: 75px;&quot;/&gt;&lt;/p&gt;', '2017-06-22 18:07:39', null);
INSERT INTO `cmf_servicecontent` VALUES ('11', '网站制作', '20180730\\52fd31654770fb71da32e93dbf66081d.png##20180730\\312a807ae5c461a5549b2bdc949cd754.png', '18658876499', '1500.00', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170623/594c6d7e3218c.png&quot; title=&quot;1.png&quot; alt=&quot;1.png&quot; style=&quot;width: 370px; height: 850px;&quot; height=&quot;850&quot; width=&quot;370&quot; vspace=&quot;0&quot; border=&quot;0&quot;/&gt;&lt;/p&gt;', '&lt;p&gt;&lt;img src=&quot;./data/upload/ueditor/20170623/594c6d94eec32.png&quot; title=&quot;流程.png&quot; alt=&quot;流程.png&quot; style=&quot;width: 370px; height: 73px;&quot; height=&quot;73&quot; width=&quot;370&quot; vspace=&quot;0&quot; border=&quot;0&quot;/&gt;&lt;/p&gt;', '2017-06-23 09:25:22', null);

-- ----------------------------
-- Table structure for cmf_servicejd
-- ----------------------------
DROP TABLE IF EXISTS `cmf_servicejd`;
CREATE TABLE `cmf_servicejd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) DEFAULT NULL COMMENT '所属服务',
  `name` text COMMENT '所有进度名',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='服务进度表';

-- ----------------------------
-- Records of cmf_servicejd
-- ----------------------------
INSERT INTO `cmf_servicejd` VALUES ('2', '天猫转让', '合同签订,\r\n名称校准,\r\n地址审批,\r\n网上申报', '2017-06-08 13:20:12', null);
INSERT INTO `cmf_servicejd` VALUES ('4', '资质转让', '合同签订,\r\n名称校准,\r\n地址审批,\r\n网上申报', '2017-06-08 13:22:56', null);
INSERT INTO `cmf_servicejd` VALUES ('5', '公司注册', '材料签字,\r\n工商登记,\r\n执照刻章,\r\n税务办理,\r\n银行开户\r\n', '2017-06-09 16:36:18', null);
INSERT INTO `cmf_servicejd` VALUES ('6', '税收规划', '财税管理，\r\n财税管理', '2017-09-11 18:31:59', null);
INSERT INTO `cmf_servicejd` VALUES ('11', '审计', '进度1,\r\n进度2', '2018-07-31 11:13:11', null);

-- ----------------------------
-- Table structure for cmf_slide
-- ----------------------------
DROP TABLE IF EXISTS `cmf_slide`;
CREATE TABLE `cmf_slide` (
  `slide_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '1首页幻灯片',
  `type` tinyint(2) NOT NULL COMMENT '幻灯片分类 id',
  `title` varchar(100) NOT NULL COMMENT '幻灯片名称',
  `picture` mediumtext COMMENT '幻灯片图片',
  `link` varchar(150) DEFAULT NULL COMMENT '幻灯片链接',
  `remark` varchar(255) DEFAULT NULL COMMENT '幻灯片描述',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，2不显示',
  `sort` int(10) DEFAULT '0' COMMENT '排序',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`slide_id`),
  KEY `slide_cid` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

-- ----------------------------
-- Records of cmf_slide
-- ----------------------------
INSERT INTO `cmf_slide` VALUES ('4', '1', '1', '20180725\\9defb4fc2afbdf6f6b14ceb6b275258d.png', '', '', '1', '2', '2018-07-25 17:13:36', '2018-09-10 09:32:21');
INSERT INTO `cmf_slide` VALUES ('5', '1', '2', '20180725\\28eb2f13ee8eb01dd4e8f991073114d3.png', '', '', '1', '1', '2018-07-25 18:52:07', '2018-07-26 13:38:05');

-- ----------------------------
-- Table structure for cmf_system_deploy
-- ----------------------------
DROP TABLE IF EXISTS `cmf_system_deploy`;
CREATE TABLE `cmf_system_deploy` (
  `deploy_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `site_name` varchar(50) NOT NULL COMMENT '网站名称',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '客服电话',
  `email` varchar(50) NOT NULL COMMENT '邮箱地址',
  `archival_information` varchar(255) NOT NULL COMMENT '备案信息',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keyword` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_describe` varchar(500) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  PRIMARY KEY (`deploy_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='cmf_system_deploy 系统配置参数表';

-- ----------------------------
-- Records of cmf_system_deploy
-- ----------------------------
INSERT INTO `cmf_system_deploy` VALUES ('1', '一颗松', '0446-856245', '2168350480@qq.com', '浙ICP备17026863号-2', '', '', '');

-- ----------------------------
-- Table structure for cmf_users
-- ----------------------------
DROP TABLE IF EXISTS `cmf_users`;
CREATE TABLE `cmf_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码；sp_password加密',
  `salt` varchar(15) NOT NULL DEFAULT '' COMMENT '盐值',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户美名',
  `realname` varchar(50) DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `user_url` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人网站',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像，相对于upload/avatar目录',
  `sex` smallint(1) DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` date DEFAULT '2000-01-01' COMMENT '生日',
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证 3删除',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `user_type` tinyint(1) DEFAULT '1' COMMENT '用户类型，1:管理员 ;2:员工；3会员',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '金币',
  `last_login_ip` varchar(16) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '最后登录时间',
  `create_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '注册时间',
  `update_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`nickname`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of cmf_users
-- ----------------------------
INSERT INTO `cmf_users` VALUES ('1', 'admin', '544667e6115055cf2e40fc34a4163039', 'Yaworizh', 'admin', '', '', '2168350480@qq.com', '', null, '0', '2000-01-01', null, '', '1', '0', '1', '0', '0.0.0.0', '2018-09-11 09:00:39', '2017-05-25 11:04:00', '2018-09-11 09:00:39');
INSERT INTO `cmf_users` VALUES ('9', 'leiquan', 'b2ae483e30f9234703195f87ceaa3ea8', 'RNS4UAEi', '', '雷全', '18806526189', '', '', null, '0', '2000-01-01', null, '', '1', '0', '2', '0', '::1', '2018-09-05 11:26:57', '2018-09-04 16:38:27', '2018-09-05 11:26:57');
INSERT INTO `cmf_users` VALUES ('8', 'liudang', '5c0ef973eccd976162d6850e898570d4', 'MMD9CuBr', '', '', '', '1015884825@qq.com', '', null, '0', '2000-01-01', null, '', '1', '0', '2', '0', null, '2000-01-01 00:00:00', '2018-07-27 15:26:47', '2018-07-27 15:26:47');

-- ----------------------------
-- Table structure for cmf_user_account
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_account`;
CREATE TABLE `cmf_user_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户id',
  `account` varchar(50) NOT NULL DEFAULT '' COMMENT '账号',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `type` tinyint(1) NOT NULL COMMENT '类型 1 支付宝 2微信  3银联',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_account_uid_unique` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户第三方账号绑定表（提现使用，非登录）';

-- ----------------------------
-- Records of cmf_user_account
-- ----------------------------
INSERT INTO `cmf_user_account` VALUES ('1', '126', 'leiquan09@163.com', '雷全', '1', '2018-08-29 17:55:10', '2018-08-29 17:55:10');

-- ----------------------------
-- Table structure for cmf_user_bind
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_bind`;
CREATE TABLE `cmf_user_bind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `auth_key` varchar(100) DEFAULT '' COMMENT '第三方账号登录标识，例如微信的unionid',
  `openid` varchar(100) DEFAULT '',
  `detail` varchar(255) DEFAULT '',
  `type` tinyint(1) DEFAULT '1' COMMENT '第三方登录类型 1 微信 2 QQ 3 微博',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_user_bind
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_user_data
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_data`;
CREATE TABLE `cmf_user_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `token` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `token_detail` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `device` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '设备号',
  `platform` tinyint(2) DEFAULT '0' COMMENT '1：安卓，2：IOS，3：小程序，0：未知设备',
  `registration_id` varchar(100) CHARACTER SET utf8 DEFAULT '' COMMENT '个推的：clientid',
  `open_id` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cmf_user_data
-- ----------------------------
INSERT INTO `cmf_user_data` VALUES ('8', '123', '6b40c19bff008afac19e17c2a85f1ddc', '{\"uid\":123,\"time\":1534156823,\"ip\":\"::1\",\"randstr\":\"uZVgg21LyVRF\",\"effect_time\":1535452823,\"device\":\"340881\"}', '340881', '1', '7249d47a4b4a4dd754ba1c0bc5bc', null, '2018-08-13 18:40:23');
INSERT INTO `cmf_user_data` VALUES ('9', '124', 'd680c582e7ab1a2a8238fd3e7bbff669', '{\"uid\":\"124\",\"time\":1535452648,\"ip\":\"::1\",\"randstr\":\"2yJscm18QU9J\",\"effect_time\":1536748648,\"device\":\"340881\"}', '340881', '0', '', null, '2018-08-28 18:37:28');
INSERT INTO `cmf_user_data` VALUES ('10', '125', '1fdf9f546aec7a89b1af878e15bb85ec', '{\"uid\":\"125\",\"time\":1535520357,\"ip\":\"::1\",\"randstr\":\"wrWWNCD4MdX6\",\"effect_time\":1536816357,\"device\":\"\"}', '', '0', '', null, '2018-08-29 13:25:57');
INSERT INTO `cmf_user_data` VALUES ('11', '129', '58e8149c80c618d6f326925a34ad50c0', '{\"uid\":\"129\",\"time\":1535520782,\"ip\":\"::1\",\"randstr\":\"UqEODwvmpeIS\",\"effect_time\":1536816782,\"device\":\"340881\"}', '340881', '0', '', null, '2018-08-29 13:33:02');
INSERT INTO `cmf_user_data` VALUES ('12', '126', '1a4c6672f06546aa01d501929f8ed367', '{\"uid\":126,\"time\":1535522053,\"ip\":\"::1\",\"randstr\":\"pgPIFvGFQ277\",\"effect_time\":1536818053,\"device\":\"340881\"}', '340881', '0', '', null, '2018-08-29 13:54:13');
INSERT INTO `cmf_user_data` VALUES ('16', '9', 'b0623b32ad553033f3a0a7e7a5897754', '{\"uid\":9,\"time\":1536118017,\"ip\":\"::1\",\"randstr\":\"JVPynGau7iXs\",\"effect_time\":1537414017,\"device\":\"340881\"}', '340881', '0', '', null, '2018-09-05 11:26:57');

-- ----------------------------
-- Table structure for cmf_user_favorites
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_favorites`;
CREATE TABLE `cmf_user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL COMMENT '用户 id',
  `title` varchar(255) DEFAULT NULL COMMENT '收藏内容的标题',
  `url` varchar(255) DEFAULT NULL COMMENT '收藏内容的原文地址，不带域名',
  `description` varchar(500) DEFAULT NULL COMMENT '收藏内容的描述',
  `table` varchar(50) DEFAULT NULL COMMENT '收藏实体以前所在表，不带前缀',
  `object_id` int(11) DEFAULT NULL COMMENT '收藏内容原来的主键id',
  `createtime` int(11) DEFAULT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏表';

-- ----------------------------
-- Records of cmf_user_favorites
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_withdraworder
-- ----------------------------
DROP TABLE IF EXISTS `cmf_withdraworder`;
CREATE TABLE `cmf_withdraworder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `money` double(11,2) DEFAULT NULL COMMENT '提现积分',
  `account` char(50) DEFAULT NULL COMMENT '提现账号',
  `realname` char(15) DEFAULT NULL COMMENT '提现账号绑定姓名',
  `status` tinyint(1) DEFAULT '1' COMMENT '1未处理 2已处理 3已退回',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='提现订单表';

-- ----------------------------
-- Records of cmf_withdraworder
-- ----------------------------
INSERT INTO `cmf_withdraworder` VALUES ('10', '58', '50.00', '1123', 'yuxi', '3', '2017-08-29 14:20:34');
INSERT INTO `cmf_withdraworder` VALUES ('11', '58', '50.00', '123', 'yuxi', '2', '2017-08-29 14:21:01');
INSERT INTO `cmf_withdraworder` VALUES ('12', '100', '10000.00', '456', '雷全', '2', '2018-07-23 11:22:49');
INSERT INTO `cmf_withdraworder` VALUES ('13', '123', '5000.00', '36589', '雷全', '2', '2018-08-16 14:22:15');
INSERT INTO `cmf_withdraworder` VALUES ('14', '123', '600.00', '36589', '雷全', '1', '2018-08-16 14:24:08');
