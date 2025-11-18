/**
 * Empty State Component
 */

import React from 'react';
import {View, Text, StyleSheet} from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import Button from './Button';
import {colors, typography, spacing} from '../utils/theme';

const EmptyState = ({
  icon = 'file-tray-outline',
  title = 'Aucun résultat',
  message = 'Aucune donnée disponible pour le moment',
  actionLabel,
  onActionPress,
}) => {
  return (
    <View style={styles.container}>
      <View style={styles.iconContainer}>
        <Icon name={icon} size={80} color={colors.grayLight} />
      </View>

      <Text style={styles.title}>{title}</Text>
      <Text style={styles.message}>{message}</Text>

      {actionLabel && onActionPress && (
        <View style={styles.action}>
          <Button
            title={actionLabel}
            onPress={onActionPress}
            variant="primary"
            icon="add-circle"
          />
        </View>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: spacing.xl,
    backgroundColor: colors.background,
  },
  iconContainer: {
    marginBottom: spacing.xl,
    opacity: 0.5,
  },
  title: {
    fontSize: typography.fontSize.xl,
    fontWeight: 'bold',
    color: colors.text,
    marginBottom: spacing.sm,
    textAlign: 'center',
  },
  message: {
    fontSize: typography.fontSize.base,
    color: colors.textLight,
    textAlign: 'center',
    marginBottom: spacing.xl,
  },
  action: {
    marginTop: spacing.base,
  },
});

export default EmptyState;
