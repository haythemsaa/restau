/**
 * Reusable Badge Component
 */

import React from 'react';
import {View, Text, StyleSheet} from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import {colors, typography, spacing, borderRadius} from '../utils/theme';

const Badge = ({
  label,
  variant = 'primary', // primary, success, warning, danger, info, gray
  size = 'medium', // small, medium, large
  icon,
  style,
}) => {
  const getVariantStyles = () => {
    switch (variant) {
      case 'success':
        return {
          backgroundColor: `${colors.success}15`,
          color: colors.success,
        };
      case 'warning':
        return {
          backgroundColor: `${colors.warning}15`,
          color: colors.warning,
        };
      case 'danger':
        return {
          backgroundColor: `${colors.danger}15`,
          color: colors.danger,
        };
      case 'info':
        return {
          backgroundColor: `${colors.info}15`,
          color: colors.info,
        };
      case 'gray':
        return {
          backgroundColor: `${colors.gray}15`,
          color: colors.gray,
        };
      default:
        return {
          backgroundColor: `${colors.primary}15`,
          color: colors.primary,
        };
    }
  };

  const getSizeStyles = () => {
    switch (size) {
      case 'small':
        return {
          paddingHorizontal: spacing.xs,
          paddingVertical: 2,
          fontSize: typography.fontSize.xs,
        };
      case 'large':
        return {
          paddingHorizontal: spacing.base,
          paddingVertical: spacing.xs,
          fontSize: typography.fontSize.base,
        };
      default:
        return {
          paddingHorizontal: spacing.sm,
          paddingVertical: 4,
          fontSize: typography.fontSize.sm,
        };
    }
  };

  const variantStyles = getVariantStyles();
  const sizeStyles = getSizeStyles();

  return (
    <View
      style={[
        styles.badge,
        {
          backgroundColor: variantStyles.backgroundColor,
          paddingHorizontal: sizeStyles.paddingHorizontal,
          paddingVertical: sizeStyles.paddingVertical,
        },
        style,
      ]}>
      {icon && (
        <Icon
          name={icon}
          size={sizeStyles.fontSize}
          color={variantStyles.color}
          style={styles.icon}
        />
      )}
      <Text
        style={[
          styles.text,
          {
            color: variantStyles.color,
            fontSize: sizeStyles.fontSize,
          },
        ]}>
        {label}
      </Text>
    </View>
  );
};

const styles = StyleSheet.create({
  badge: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
    borderRadius: borderRadius.sm,
  },
  icon: {
    marginRight: 4,
  },
  text: {
    fontWeight: '600',
  },
});

export default Badge;
